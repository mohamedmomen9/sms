<?php

namespace Modules\Subject\Filament\Resources\CourseOfferingResource\Pages;

use Modules\Subject\Filament\Resources\CourseOfferingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseOffering extends EditRecord
{
    protected static string $resource = CourseOfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            
            Actions\Action::make('saveAndClose')
                ->label('Save & Close')
                ->action(function () {
                    $this->save(shouldRedirect: false);
                    return redirect($this->getResource()::getUrl('index'));
                }),
                
            $this->getCancelFormAction(),
        ];
    }
}
