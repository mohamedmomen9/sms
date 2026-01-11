<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Curriculum\Filament\Resources\CurriculumResource;

class CreateCurriculum extends CreateRecord
{
    protected static string $resource = CurriculumResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncSubjects();
    }

    protected function syncSubjects(): void
    {
        $proxiedSubjects = $this->data['proxied_subjects'] ?? [];
        $activeSubjectIds = [];

        foreach ($proxiedSubjects as $group) {
            if (isset($group['subjects']) && is_array($group['subjects'])) {
                foreach ($group['subjects'] as $subjectData) {
                    $subjectId = $subjectData['id'] ?? null;
                    if ($subjectId) {
                        $activeSubjectIds[$subjectId] = [
                            'is_mandatory' => (bool) ($subjectData['is_mandatory'] ?? true),
                            'credit_hours' => (float) ($subjectData['credit_hours'] ?? 3.0),
                        ];
                    }
                }
            }
        }

        $this->record->subjects()->sync($activeSubjectIds);
    }
}
