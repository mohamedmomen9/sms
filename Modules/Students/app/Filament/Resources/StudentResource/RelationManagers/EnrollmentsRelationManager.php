<?php

namespace Modules\Students\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_offering_id')
                    ->relationship('courseOffering', 'id', function (Builder $query) {
                        return $query->with(['subject', 'term']);
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->term->name} - {$record->subject->name} ({$record->section_number})")
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('courseOffering.term.name')
                    ->label('Term')
                    ->sortable(),
                Tables\Columns\TextColumn::make('courseOffering.subject.name')
                    ->label('Subject')
                    ->sortable(),
                Tables\Columns\TextColumn::make('courseOffering.section_number')
                    ->label('Section'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
