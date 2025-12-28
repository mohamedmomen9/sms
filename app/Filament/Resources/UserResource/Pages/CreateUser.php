<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        // Automatically assign 'user' role if no roles were assigned and user is not admin
        $user = $this->record;
        
        if ($user->roles->isEmpty() && !$user->is_admin) {
            $user->assignRole('user');
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
