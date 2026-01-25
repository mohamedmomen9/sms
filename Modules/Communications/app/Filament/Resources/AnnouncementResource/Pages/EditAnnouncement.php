<?php

namespace Modules\Communications\Filament\Resources\AnnouncementResource\Pages;

use Modules\Communications\Filament\Resources\AnnouncementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditAnnouncement extends EditRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Original image should never change once set
        // If the original exists in the record, preserve it
        if ($this->record && $this->record->image) {
            $data['image'] = $this->record->image;
        }

        // If somehow image is still empty but cropped exists, create original from cropped
        if (empty($data['image']) && !empty($data['cropped_image'])) {
            $croppedPath = $data['cropped_image'];

            if (Storage::disk('public')->exists($croppedPath)) {
                $extension = pathinfo($croppedPath, PATHINFO_EXTENSION);
                $originalPath = 'announcements/original/' . uniqid() . '.' . $extension;

                Storage::disk('public')->copy($croppedPath, $originalPath);
                $data['image'] = $originalPath;
            }
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
