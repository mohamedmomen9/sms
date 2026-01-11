<?php

namespace Modules\Students\Filament\Resources\StudentResource\Pages;

use Modules\Students\Filament\Resources\StudentResource;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;
}
