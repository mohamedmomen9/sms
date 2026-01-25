<?php

namespace Modules\Family\Filament\Resources\GuardianResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Family\Filament\Resources\GuardianResource;

use Illuminate\Support\Str;
use Modules\Students\Models\Student;

class CreateGuardian extends CreateRecord
{
    protected static string $resource = GuardianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Str::random(16);

        if (isset($data['student_id'])) {
            $student = Student::find($data['student_id']);
            if ($student) {
                $data['campus_id'] = $student->campus_id;
            }
        }

        return $data;
    }
}
