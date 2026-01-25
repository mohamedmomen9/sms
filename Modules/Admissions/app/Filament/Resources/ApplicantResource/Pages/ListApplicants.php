<?php

namespace Modules\Admissions\Filament\Resources\ApplicantResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Admissions\Filament\Resources\ApplicantResource;

class ListApplicants extends ListRecords
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
