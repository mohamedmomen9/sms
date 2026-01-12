<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Subject\Models\CourseSchedule;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Class Schedule';

    protected static ?string $icon = 'heroicon-o-calendar-days';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day')
                    ->label(__('Day'))
                    ->options(array_combine(CourseSchedule::DAYS, array_map(fn($d) => __($d), CourseSchedule::DAYS)))
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->label(__('Start Time'))
                    ->seconds(false)
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label(__('End Time'))
                    ->seconds(false)
                    ->required()
                    ->after('start_time'),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day')
            ->columns([
                Tables\Columns\TextColumn::make('day')
                    ->label(__('Day'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sunday' => 'danger',
                        'Monday' => 'primary',
                        'Tuesday' => 'success',
                        'Wednesday' => 'warning',
                        'Thursday' => 'info',
                        'Friday' => 'gray',
                        'Saturday' => 'secondary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('start_time')
                    ->label(__('Start Time'))
                    ->time('g:i A'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label(__('End Time'))
                    ->time('g:i A'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add Session')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('day')
            ->reorderable(false);
    }
}
