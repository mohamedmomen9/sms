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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
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
