<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Students\Models\Student;
use Modules\Students\Models\StudentImage;

class DemoStudentFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Assign images to a fixed set of students
        $students = Student::orderBy('id')->limit(10)->get();

        foreach ($students as $student) {
            StudentImage::firstOrCreate(
                ['student_id' => $student->id],
                [
                    'image_pose_center' => 'storage/student-images/demo/' . $student->id . '_center.jpg',
                    'image_pose_left'   => 'storage/student-images/demo/' . $student->id . '_left.jpg',
                    'image_pose_right'  => 'storage/student-images/demo/' . $student->id . '_right.jpg',
                    'image_pose_down'   => 'storage/student-images/demo/' . $student->id . '_down.jpg',
                    'status'            => 'verified',
                ]
            );
        }
    }
}
