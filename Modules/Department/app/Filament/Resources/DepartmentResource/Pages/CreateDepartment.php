<?php

namespace Modules\Department\Filament\Resources\DepartmentResource\Pages;

use Modules\Department\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
