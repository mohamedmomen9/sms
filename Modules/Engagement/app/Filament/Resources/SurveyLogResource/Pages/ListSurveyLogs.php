<?php

namespace Modules\Engagement\Filament\Resources\SurveyLogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Engagement\Filament\Resources\SurveyLogResource;

class ListSurveyLogs extends ListRecords
{
    protected static string $resource = SurveyLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
