<?php

namespace Modules\Subject\DTOs;

class CourseDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public ?string $section = null,
        public ?array $schedule = null,
        public ?string $room = null,
        public ?string $teacherName = null,
        public ?int $enrollmentCount = null,
        public ?string $termName = null
    ) {}
}
