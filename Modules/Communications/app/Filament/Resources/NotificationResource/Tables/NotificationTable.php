<?php

namespace Modules\Communications\Filament\Resources\NotificationResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class NotificationTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('title')
                ->label(__('communications::app.Title'))
                ->searchable()
                ->sortable()
                ->limit(50)
                ->tooltip(fn($record) => $record->title),

            TextColumn::make('subtitle')
                ->label(__('communications::app.Subtitle'))
                ->limit(40)
                ->toggleable()
                ->placeholder('-'),

            TextColumn::make('logs_count')
                ->label(__('communications::app.Logs Count'))
                ->counts('logs')
                ->badge()
                ->color('info')
                ->sortable(),

            TextColumn::make('created_at')
                ->label(__('communications::app.Created'))
                ->dateTime('M d, Y H:i')
                ->sortable(),

            TextColumn::make('updated_at')
                ->label(__('communications::app.Updated'))
                ->dateTime('M d, Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            Filter::make('date_range')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('from')
                        ->label(__('app.From')),
                    \Filament\Forms\Components\DatePicker::make('until')
                        ->label(__('app.To')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
        ];
    }
}
