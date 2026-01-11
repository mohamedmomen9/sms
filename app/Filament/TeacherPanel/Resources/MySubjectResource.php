<?php

namespace App\Filament\TeacherPanel\Resources;

use App\Filament\TeacherPanel\Resources\MySubjectResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Subject\Models\Subject;

class MySubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'My Subjects';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('Subject');
    }

    public static function getPluralModelLabel(): string
    {
        return __('My Subjects');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Subjects');
    }

    public static function getEloquentQuery(): Builder
    {
        $teacher = auth('teacher')->user();
        
        if (!$teacher) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        $directSubjectIds = $teacher->subjects()->pluck('subjects.id')->toArray();
        $facultyIds = $teacher->faculties()->pluck('faculties.id')->toArray();
        
        return parent::getEloquentQuery()
            ->where(function ($query) use ($directSubjectIds, $facultyIds) {
                $query->whereIn('id', $directSubjectIds);
                
                if (!empty($facultyIds)) {
                    $query->orWhere(function ($q) use ($facultyIds) {
                        $q->whereIn('faculty_id', $facultyIds)
                            ->orWhereHas('department', function ($dq) use ($facultyIds) {
                                $dq->whereIn('faculty_id', $facultyIds);
                            });
                    });
                }
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label(__('Code'))
                    ->disabled(),
                TextInput::make('name')
                    ->label(__('Name'))
                    ->disabled(),
                TextInput::make('faculty.name')
                    ->label(__('Faculty'))
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('faculty.name')
                    ->label(__('Faculty'))
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label(__('Department'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('faculty_id')
                    ->label(__('Faculty'))
                    ->relationship('faculty', 'name')
                    ->preload(),
            ])
            ->actions([
                ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMySubjects::route('/'),
            'view' => Pages\ViewMySubject::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
