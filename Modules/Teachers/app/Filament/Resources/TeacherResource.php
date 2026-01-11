<?php

namespace Modules\Teachers\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;
use Modules\Teachers\Filament\Resources\TeacherResource\Pages;
use Modules\Teachers\Models\Teacher;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Academic Management';

    public static function getModelLabel(): string
    {
        return __('Teacher');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Teachers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Personal Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        TextInput::make('phone')
                            ->label(__('Phone'))
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('qualification')
                            ->label(__('Qualification'))
                            ->maxLength(255),
                    ])
                    ->columns(2),
                    
                Section::make(__('Campus Assignment'))
                    ->schema([
                        Select::make('campus_id')
                            ->label(__('Campus'))
                            ->relationship('campus', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ]),
                    
                Section::make(__('Faculty & Subject Assignments'))
                    ->description(__('Select the faculties this teacher belongs to and the subjects they teach'))
                    ->schema([
                        Select::make('faculties')
                            ->label(__('Faculties'))
                            ->multiple()
                            ->relationship('faculties', 'name')
                            ->options(function () {
                                return Faculty::all()->mapWithKeys(function ($faculty) {
                                    $name = is_array($faculty->name) 
                                        ? ($faculty->name[app()->getLocale()] ?? $faculty->name['en'] ?? '') 
                                        : $faculty->name;
                                    return [$faculty->id => $name];
                                });
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('subjects', [])),
                            
                        Select::make('subjects')
                            ->label(__('Subjects'))
                            ->multiple()
                            ->relationship('subjects', 'name')
                            ->options(function (Get $get) {
                                $facultyIds = $get('faculties') ?? [];
                                
                                if (empty($facultyIds)) {
                                    return Subject::with('faculty')
                                        ->orderBy('faculty_id')
                                        ->get()
                                        ->mapWithKeys(function ($subject) {
                                            $name = is_array($subject->name) 
                                                ? ($subject->name[app()->getLocale()] ?? $subject->name['en'] ?? '') 
                                                : $subject->name;
                                            $facultyName = '';
                                            if ($subject->faculty) {
                                                $facultyName = is_array($subject->faculty->name) 
                                                    ? ($subject->faculty->name[app()->getLocale()] ?? $subject->faculty->name['en'] ?? '') 
                                                    : $subject->faculty->name;
                                                $facultyName = " [{$facultyName}]";
                                            }
                                            return [$subject->id => $name . $facultyName];
                                        });
                                }
                                
                                return Subject::where(function ($query) use ($facultyIds) {
                                    $query->whereIn('faculty_id', $facultyIds)
                                        ->orWhereHas('department', function ($q) use ($facultyIds) {
                                            $q->whereIn('faculty_id', $facultyIds);
                                        });
                                })
                                ->with('faculty', 'department.faculty')
                                ->get()
                                ->groupBy(function ($subject) {
                                    if ($subject->faculty) {
                                        return is_array($subject->faculty->name) 
                                            ? ($subject->faculty->name[app()->getLocale()] ?? $subject->faculty->name['en'] ?? 'No Faculty') 
                                            : $subject->faculty->name;
                                    }
                                    if ($subject->department && $subject->department->faculty) {
                                        return is_array($subject->department->faculty->name) 
                                            ? ($subject->department->faculty->name[app()->getLocale()] ?? $subject->department->faculty->name['en'] ?? 'No Faculty') 
                                            : $subject->department->faculty->name;
                                    }
                                    return __('No Faculty');
                                })
                                ->flatMap(function ($subjects, $facultyName) {
                                    return $subjects->mapWithKeys(function ($subject) use ($facultyName) {
                                        $name = is_array($subject->name) 
                                            ? ($subject->name[app()->getLocale()] ?? $subject->name['en'] ?? '') 
                                            : $subject->name;
                                        return [$subject->id => "{$name} [{$facultyName}]"];
                                    });
                                });
                            })
                            ->searchable()
                            ->preload()
                            ->helperText(__('Subjects are filtered based on selected faculties')),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable(),
                TextColumn::make('qualification')
                    ->label(__('Qualification'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('campus.name')
                    ->label(__('Campus'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('faculties.name')
                    ->label(__('Faculties'))
                    ->badge()
                    ->separator(', ')
                    ->toggleable(),
                TextColumn::make('subjects_count')
                    ->label(__('Subjects'))
                    ->counts('subjects')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('campus_id')
                    ->label(__('Campus'))
                    ->relationship('campus', 'name'),
                SelectFilter::make('faculties')
                    ->label(__('Faculty'))
                    ->relationship('faculties', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
