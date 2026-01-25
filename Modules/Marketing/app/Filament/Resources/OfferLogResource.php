<?php

namespace Modules\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Marketing\Filament\Resources\OfferLogResource\Pages;
use Modules\Marketing\Models\OfferLog;

class OfferLogResource extends Resource
{
    protected static ?string $model = OfferLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Offer Analytics';

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
                Forms\Components\TextInput::make('offer_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('entity_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('entity_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_favorite')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('offer.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entity_type')
                    ->label('Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'student' => 'success',
                        'teacher' => 'warning',
                        'parent' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('entity_id')
                    ->label('User ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_favorite')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entity_type')
                    ->options([
                        'student' => 'Student',
                        'teacher' => 'Teacher',
                        'parent' => 'Parent',
                    ]),
                Tables\Filters\TernaryFilter::make('is_favorite'),
            ])
            ->actions([
                // Read only
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
            'index' => Pages\ListOfferLogs::route('/'),
        ];
    }
}
