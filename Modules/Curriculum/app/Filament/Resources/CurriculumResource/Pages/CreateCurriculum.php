<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Curriculum\Filament\Resources\CurriculumResource;

class CreateCurriculum extends CreateRecord
{
    protected static string $resource = CurriculumResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $faculties = $data['faculties'] ?? [];
        $departments = $data['departments'] ?? [];
        $proxiedSubjects = $data['proxied_subjects'] ?? [];

        // Remove relationship data from main payload
        unset($data['faculties'], $data['departments'], $data['proxied_subjects']);

        // Create the record
        $record = static::getModel()::create($data);

        // Sync Relationships
        $record->faculties()->sync($faculties);
        $record->departments()->sync($departments);

        // Sync Subjects from nested Repeater structure
        $activeSubjectIds = [];
        foreach ($proxiedSubjects as $group) {
            if (isset($group['subjects']) && is_array($group['subjects'])) {
                foreach ($group['subjects'] as $subjectData) {
                    $subjectId = $subjectData['id'] ?? null;
                    if ($subjectId) {
                        // Handle is_mandatory: if key missing, assume false (unchecked) rather than true
                        $isMandatory = isset($subjectData['is_mandatory']) 
                            ? (bool) $subjectData['is_mandatory'] 
                            : false;

                        $activeSubjectIds[$subjectId] = [
                            'is_mandatory' => $isMandatory,
                            'credit_hours' => max(0, (float) ($subjectData['credit_hours'] ?? 3.0)),
                        ];
                    }
                }
            }
        }
        $record->subjects()->sync($activeSubjectIds);

        return $record;
    }
}
