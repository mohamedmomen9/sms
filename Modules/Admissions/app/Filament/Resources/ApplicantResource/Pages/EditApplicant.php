<?php

namespace Modules\Admissions\Filament\Resources\ApplicantResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Admissions\Filament\Resources\ApplicantResource;

class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
