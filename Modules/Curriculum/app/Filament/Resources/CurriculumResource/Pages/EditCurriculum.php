<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Curriculum\Filament\Resources\CurriculumResource;

class EditCurriculum extends EditRecord
{
    protected static string $resource = CurriculumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $faculties = $data['faculties'] ?? [];
        $departments = $data['departments'] ?? [];
        $proxiedSubjects = $data['proxied_subjects'] ?? [];

        // Remove relationship data from main payload
        unset($data['faculties'], $data['departments'], $data['proxied_subjects']);

        // Update the record
        $record->update($data);

        // Sync Relationships
        $record->faculties()->sync($faculties);
        $record->departments()->sync($departments);

        // Sync Subjects from nested Repeater structure
        $activeSubjectIds = [];
        $subjectPrerequisitesUpdates = [];

        foreach ($proxiedSubjects as $group) {
            if (isset($group['subjects']) && is_array($group['subjects'])) {
                foreach ($group['subjects'] as $subjectData) {
                    $subjectId = $subjectData['id'] ?? null;
                    if ($subjectId) {
                        // Handle is_mandatory
                        $isMandatory = isset($subjectData['is_mandatory']) 
                            ? (bool) $subjectData['is_mandatory'] 
                            : false;

                        $usesGpa = isset($subjectData['uses_gpa']) 
                            ? (bool) $subjectData['uses_gpa'] 
                            : false;
                        
                        $activeSubjectIds[$subjectId] = [
                            'is_mandatory' => $isMandatory,
                            'credit_hours' => max(0, (float) ($subjectData['credit_hours'] ?? 3.0)),
                            'uses_gpa' => $usesGpa,
                            'gpa_requirement' => $usesGpa ? ($subjectData['gpa_requirement'] ?? null) : null,
                        ];

                        // Collect prerequisite updates
                        if (isset($subjectData['prerequisites_ids']) && is_array($subjectData['prerequisites_ids'])) {
                             $subjectPrerequisitesUpdates[$subjectId] = $subjectData['prerequisites_ids'];
                        }
                    }
                }
            }
        }
        $record->subjects()->sync($activeSubjectIds);

        // Process Prerequisite Updates
        foreach ($subjectPrerequisitesUpdates as $subjId => $prereqIds) {
             \Modules\Subject\Models\Subject::find($subjId)?->prerequisites()->sync($prereqIds);
        }

        return $record;
    }
}
