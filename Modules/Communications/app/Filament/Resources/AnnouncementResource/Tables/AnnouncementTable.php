<?php

namespace Modules\Communications\Filament\Resources\AnnouncementResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Communications\Models\Announcement;

class AnnouncementTable
{
    public static function columns(): array
    {
        return [
            ImageColumn::make('cropped_image')
                ->label(__('communications::app.Image'))
                ->circular()
                ->defaultImageUrl(fn($record) => $record->image ? asset('storage/' . $record->image) : url('/images/placeholder.png'))
                ->toggleable(),

            ImageColumn::make('image')
                ->label(__('communications::app.Original Image'))
                ->square()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('title')
                ->label(__('communications::app.Title'))
                ->searchable()
                ->sortable()
                ->limit(50)
                ->tooltip(fn($record) => $record->title),

            TextColumn::make('type')
                ->label(__('communications::app.Type'))
                ->badge()
                ->formatStateUsing(fn(string $state): string => __("communications::app.{$state}"))
                ->color(fn(string $state): string => match ($state) {
                    'news' => 'info',
                    'events' => 'success',
                    'lectures' => 'warning',
                    'announcements' => 'primary',
                    default => 'secondary',
                })
                ->sortable(),

            TextColumn::make('campus.name')
                ->label(__('communications::app.Campus'))
                ->placeholder(__('communications::app.All Campuses'))
                ->searchable()
                ->sortable(),

            TextColumn::make('date')
                ->label(__('communications::app.Date'))
                ->date('M d, Y')
                ->sortable(),

            IconColumn::make('is_active')
                ->label(__('communications::app.Status'))
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),

            TextColumn::make('created_at')
                ->label(__('communications::app.Created'))
                ->dateTime('M d, Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            SelectFilter::make('type')
                ->label(__('communications::app.Type'))
                ->options(
                    collect(Announcement::getTypes())->mapWithKeys(fn($type) => [
                        $type => __("communications::app.{$type}")
                    ])->toArray()
                ),

            SelectFilter::make('campus_id')
                ->label(__('communications::app.Campus'))
                ->relationship('campus', 'name')
                ->searchable()
                ->preload(),

            SelectFilter::make('is_active')
                ->label(__('communications::app.Status'))
                ->options([
                    '1' => __('communications::app.Active'),
                    '0' => __('communications::app.Inactive'),
                ]),

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
                            fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                        );
                }),
        ];
    }
}
