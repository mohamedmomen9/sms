<?php

namespace Modules\Disciplinary\Filament\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Disciplinary\Models\Grievance;
use Modules\Disciplinary\Filament\Resources\GrievanceResource\Pages;

class GrievanceResource extends Resource
{
    protected static ?string $model = Grievance::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Student Affairs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Student Information')->schema([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),
                Select::make('term_id')
                    ->relationship('term', 'name')
                    ->required(),
            ])->columns(2),

            Section::make('Violation Details')->schema([
                TextInput::make('violation_type')->required(),
                Textarea::make('violation_description'),
                Textarea::make('decision_text'),
                DatePicker::make('dean_date'),
                Select::make('approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ]),

            Section::make('Student Appeal')->schema([
                Textarea::make('grievance_text')
                    ->label('Appeal Text')
                    ->disabled(),
                Textarea::make('grievance_decision')
                    ->label('Appeal Decision'),
                DatePicker::make('grievance_dean_date'),
                Select::make('grievance_approval_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')->searchable(),
                TextColumn::make('student.student_id')->searchable(),
                TextColumn::make('violation_type'),
                TextColumn::make('approval_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('grievance_approval_status')
                    ->label('Appeal Status')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('approval_status'),
                SelectFilter::make('grievance_approval_status'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGrievances::route('/'),
            'create' => Pages\CreateGrievance::route('/create'),
            'edit' => Pages\EditGrievance::route('/{record}/edit'),
        ];
    }
}
