<?php

namespace Modules\Students\Services;

use Modules\Students\Models\StudentTutorial;

class TutorialService
{
    public function isCompleted(int $studentId, string $key): bool
    {
        return StudentTutorial::isCompleted($studentId, $key);
    }

    public function markCompleted(int $studentId, string $key, array $meta = []): StudentTutorial
    {
        return StudentTutorial::markCompleted($studentId, $key, $meta);
    }
}
