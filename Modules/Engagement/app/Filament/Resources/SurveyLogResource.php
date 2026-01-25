<?php

namespace Modules\Engagement\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Engagement\Filament\Resources\SurveyLogResource\Pages;
use Modules\Engagement\Models\SurveyLog;

class SurveyLogResource extends Resource
{
    protected static ?string $model = SurveyLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Engagement';

    protected static ?string $navigationLabel = 'Survey Analytics';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('survey.title')
                    ->label('Survey'),
                Forms\Components\TextInput::make('participant_type'),
                Forms\Components\TextInput::make('participant_id'),
                Forms\Components\Toggle::make('status'),
                Forms\Components\DateTimePicker::make('completed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('survey.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('participant_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'STUDENT' => 'success',
                        'TEACHER' => 'warning',
                        'PARENT' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('participant_id')
                    ->label('User ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Completed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('survey')
                    ->relationship('survey', 'title'),
                Tables\Filters\SelectFilter::make('participant_type')
                    ->options([
                        'STUDENT' => 'Student',
                        'TEACHER' => 'Teacher',
                        'PARENT' => 'Parent',
                    ]),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Completion Status'),
            ])
            ->actions([
                // Read-only
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyLogs::route('/'),
        ];
    }
}
