<?php

namespace Modules\Services\Filament\Resources\AppointmentResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Services\Filament\Resources\AppointmentResource;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;
}
