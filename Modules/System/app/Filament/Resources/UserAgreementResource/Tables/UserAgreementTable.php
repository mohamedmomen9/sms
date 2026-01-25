<?php

namespace Modules\System\Filament\Resources\UserAgreementResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserAgreementTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('agreeable_type')
                    ->label('User Type')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('agreeable_id')
                    ->label('User ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('agreement_type')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('accepted_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Read Only
            ])
            ->bulkActions([
                // Read Only
            ]);
    }
}
