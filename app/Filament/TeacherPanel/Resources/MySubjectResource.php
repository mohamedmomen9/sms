<?php

namespace App\Filament\TeacherPanel\Resources;

use App\Filament\TeacherPanel\Resources\MySubjectResource\Pages;
use App\Filament\TeacherPanel\Resources\MySubjectResource\Schemas\MySubjectForm;
use App\Filament\TeacherPanel\Resources\MySubjectResource\Tables\MySubjectTable;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
            ->schema(MySubjectForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(MySubjectTable::columns())
            ->filters(MySubjectTable::filters())
            ->actions(MySubjectTable::actions())
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
