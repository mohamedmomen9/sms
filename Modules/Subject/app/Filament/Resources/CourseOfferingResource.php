<?php

namespace Modules\Subject\Filament\Resources;

use Modules\Subject\Filament\Resources\CourseOfferingResource\Pages;
use Modules\Subject\Filament\Resources\CourseOfferingResource\RelationManagers;
use Modules\Subject\Models\CourseOffering;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseOfferingResource extends Resource
{
    protected static ?string $model = CourseOffering::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('term_id')
                    ->relationship('term', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('section_number')
                    ->required()
                    ->default('01')
                    ->maxLength(255)
                    ->rule(function (Forms\Get $get) {
                        return \Illuminate\Validation\Rule::unique('course_offerings', 'section_number')
                            ->where('term_id', $get('term_id'))
                            ->where('subject_id', $get('subject_id'))
                            ->ignore($get('id')); // Ignore current record on edit
                    }),
                Forms\Components\TextInput::make('capacity')
                    ->numeric()
                    ->default(30)
                    ->required(),
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'room_code') // Will need to getLabelFromRecordUsing to match fancy format 
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->label_name)
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('term.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('section_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Instructor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->counts('enrollments')
                    ->label('Enrolled'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseOfferings::route('/'),
            'create' => Pages\CreateCourseOffering::route('/create'),
            'edit' => Pages\EditCourseOffering::route('/{record}/edit'),
        ];
    }
}
