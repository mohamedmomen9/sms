<?php

namespace Modules\Communications\Filament\Resources\AnnouncementResource\Pages;

use Modules\Communications\Filament\Resources\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure cropped_image is set (should be from the form)
        // If image is still empty but cropped exists, copy cropped to image
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
