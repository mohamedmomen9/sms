<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Services\Models\ServiceType;
use Modules\Services\Models\ServiceRequest;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class DemoServiceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Service Types
        $types = [
            ['name' => 'Official Transcript', 'code' => 'DOC-001', 'price' => 50.00, 'days' => 3],
            ['name' => 'Student ID Replacement', 'code' => 'ID-002', 'price' => 100.00, 'days' => 7],
            ['name' => 'Graduation Certificate', 'code' => 'DOC-003', 'price' => 200.00, 'days' => 14],
        ];

        $serviceTypes = [];
        foreach ($types as $t) {
            $serviceTypes[] = ServiceType::firstOrCreate(['code' => $t['code']], [
                'name' => $t['name'],
                'price' => $t['price'],
                'duration_days' => $t['days'],
                'is_active' => true,
                'description' => $t['name'] . ' for students.',
                'requires_shipping' => $t['code'] !== 'ID-002',
            ]);
        }

        // 2. Service Requests
        $term = Term::where('is_active', true)->first();
        if (!$term) return;

        // Select fixed group of students
        $students = Student::orderBy('id')->take(50)->get();

        foreach ($students as $index => $student) {
            // Assign specific service type
            $type = $serviceTypes[$index % count($serviceTypes)];

            ServiceRequest::firstOrCreate([
                'student_id' => $student->student_id,
                'term_id' => $term->id,
                'service_type_id' => $type->id,
            ], [
                'payment_amount' => $type->price,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
        }
    }
}
