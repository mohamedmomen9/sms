<?php

namespace Modules\Services\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Services\Models\ServiceRequest;
use Modules\Services\Filament\Resources\ServiceRequestResource\Pages;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Service Management';

    public static function getNavigationGroup(): ?string
    {
        return __('services::app.Service Management');
    }

    public static function getModelLabel(): string
    {
        return __('services::app.Service Request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('services::app.Service Requests');
    }

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Request Details')->schema([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),

                Select::make('term_id')
                    ->relationship('term', 'name')
                    ->required(),

                Select::make('service_type_id')
                    ->relationship('serviceType', 'name')
                    ->required(),

                Textarea::make('notes'),

                TextInput::make('payment_amount')
                    ->numeric()
                    ->prefix('EGP'),

                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),

                Toggle::make('shipping_required'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('student.name')->searchable(),
                TextColumn::make('student.student_id')->searchable(),
                TextColumn::make('serviceType.name'),
                TextColumn::make('payment_amount')->money('EGP'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed', 'delivered' => 'success',
                        'processing' => 'info',
                        'pending' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status'),
                SelectFilter::make('payment_status'),
                SelectFilter::make('service_type_id')
                    ->relationship('serviceType', 'name'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceRequests::route('/'),
            'create' => Pages\CreateServiceRequest::route('/create'),
            'edit' => Pages\EditServiceRequest::route('/{record}/edit'),
        ];
    }
}
