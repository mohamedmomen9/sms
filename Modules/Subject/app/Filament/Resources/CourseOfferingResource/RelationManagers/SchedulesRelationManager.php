<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Subject\Models\CourseSchedule;
use Modules\Subject\Models\SessionType;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Class Schedule';

    protected static ?string $icon = 'heroicon-o-calendar-days';

    public function form(Form $form): Form
    {
        // Get the instructor options from the parent course offering
        $instructorOptions = $this->getOwnerRecord()
            ->teachers()
            ->pluck('name', 'teachers.id')
            ->toArray();

        return $form
            ->schema([
                Forms\Components\Select::make('session_type_id')
                    ->label(__('Session Type'))
                    ->options(SessionType::active()->pluck('name', 'id'))
                    ->required()
                    ->default(fn () => SessionType::where('code', 'LECT')->first()?->id)
                    ->helperText(__('Type of session (Class, Lab, Lecture, etc.)')),
                Forms\Components\Select::make('teacher_id')
                    ->label(__('Instructor'))
                    ->options($instructorOptions)
                    ->searchable()
                    ->required()
                    ->helperText(__('Select the instructor for this session')),
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
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day')
            ->columns([
                Tables\Columns\TextColumn::make('sessionType.code')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'C' => 'primary',
                        'LAB' => 'success',
                        'LECT' => 'info',
                        'PR' => 'warning',
                        'TUT' => 'gray',
                        default => 'secondary',
                    })
                    ->tooltip(fn ($record) => $record->sessionType?->name),
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
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label(__('Instructor'))
                    ->placeholder(__('Not assigned'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('session_type_id')
                    ->label(__('Session Type'))
                    ->options(SessionType::active()->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label(__('Instructor'))
                    ->options(fn () => $this->getOwnerRecord()->teachers()->pluck('name', 'teachers.id')),
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


