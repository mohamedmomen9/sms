<?php

namespace Modules\Engagement\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Engagement\Filament\Resources\SurveyResource\Pages;
use Modules\Engagement\Models\Survey;
use Modules\Campus\Models\Campus;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Engagement';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Survey Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('url')
                            ->label('Survey URL')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('target_type')
                            ->options([
                                'ALL' => 'All Users',
                                'STUDENT' => 'Students',
                                'TEACHER' => 'Teachers',
                                'PARENT' => 'Parents',
                            ])
                            ->required()
                            ->default('ALL'),
                        Forms\Components\Select::make('campus_id')
                            ->label('Campus')
                            ->options(Campus::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('All Campuses')
                            ->helperText('Leave empty to target all campuses'),
                        Forms\Components\Toggle::make('active')
                            ->label('Is Active')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->limit(30)
                    ->url(fn($record) => $record->url)
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('target_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('campus.name')
                    ->placeholder('All Campuses')
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('logs_count')
                    ->counts('logs')
                    ->label('Participants'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('target_type')
                    ->options([
                        'ALL' => 'All',
                        'STUDENT' => 'Students',
                        'TEACHER' => 'Teachers',
                        'PARENT' => 'Parents',
                    ]),
                Tables\Filters\SelectFilter::make('campus')
                    ->relationship('campus', 'name'),
                Tables\Filters\TernaryFilter::make('active'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}
