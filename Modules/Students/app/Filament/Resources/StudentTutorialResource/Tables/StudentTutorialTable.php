<?php

namespace Modules\Students\Filament\Resources\StudentTutorialResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentTutorialTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tutorial_key')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tutorial_key')
                    ->options([
                        'app_intro' => 'App Intro',
                        'schedule_guide' => 'Schedule Guide',
                        'notification_settings' => 'Notification Settings',
                        'profile_setup' => 'Profile Setup',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }
}
