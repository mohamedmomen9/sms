<?php

namespace Modules\Students\Filament\Resources\StudentResource\Pages;

use Modules\Students\Filament\Resources\StudentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
}
