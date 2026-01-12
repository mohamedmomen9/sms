<?php

namespace Modules\Subject\Contracts;

use Illuminate\Support\Collection;

interface CourseServiceInterface
{
    /**
     * Get the current active courses for the context user.
     *
     * @return Collection<int, \Modules\Subject\DTOs\CourseDTO>
     */
    public function getCurrentCourses(): Collection;
}
