<?php

namespace Modules\Subject\Factories;

use Modules\Subject\Contracts\CourseServiceInterface;
use Modules\Students\Models\Student;
use Modules\Teachers\Models\Teacher;
use Modules\Students\Services\StudentCourseService;
use Modules\Teachers\Services\TeacherCourseService;
use Exception;

class CourseServiceFactory
{
    public static function make($user): CourseServiceInterface
    {
        if ($user instanceof Student) {
            return new StudentCourseService($user);
        }

        if ($user instanceof Teacher) {
            return new TeacherCourseService($user);
        }

        throw new Exception("No course service available for user type: " . get_class($user));
    }
}
