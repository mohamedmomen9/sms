<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Services\Models\AppointmentDepartment;
use Modules\Services\Models\AppointmentPurpose;
use Modules\Services\Models\AppointmentSlot;
use Modules\Services\Models\Appointment;
use Modules\Students\Models\Student;
use Modules\Academic\Models\Term;

class DemoAppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Appointment Departments
        $depts = [
            ['name' => 'Student Affairs', 'code' => 'SA'],
            ['name' => 'Finance Office', 'code' => 'FIN'],
            ['name' => 'IT Support', 'code' => 'IT'],
        ];

        $appDepts = [];
        foreach ($depts as $d) {
            $appDepts[] = AppointmentDepartment::firstOrCreate(['code' => $d['code']], [
                'name' => ['en' => $d['name'], 'ar' => $d['name']],
                'is_active' => true
            ]);
        }

        // 2. Purposes
        foreach ($appDepts as $dept) {
            AppointmentPurpose::firstOrCreate([
                'department_id' => $dept->id,
                'name' => "General Inquiry - {$dept->code}",
            ], ['description' => 'General questions and support']);
        }

        // 3. Slots
        foreach ($appDepts as $dept) {
            AppointmentSlot::firstOrCreate([
                'department_id' => $dept->id,
                'day_of_week' => 1, // Monday
                'start_time' => '09:00:00',
            ], [
                'end_time' => '12:00:00',
                'max_capacity' => 10,
                'is_active' => true,
            ]);
        }

        // 4. Appointments
        $term = Term::where('is_active', true)->first();
        if (!$term) return;

        $students = Student::inRandomOrder()->take(20)->get();
        $slot = AppointmentSlot::first();
        $purpose = AppointmentPurpose::first();

        foreach ($students as $student) {
            if (!$slot || !$purpose) continue;

            Appointment::firstOrCreate([
                'student_id' => $student->student_id,
                'appointment_date' => now()->addDays(rand(1, 14))->format('Y-m-d'),
                'slot_id' => $slot->id,
            ], [
                'term_id' => $term->id,
                'department_id' => $slot->department_id,
                'purpose_id' => $purpose->id,
                'status' => 'booked',
            ]);
        }
    }
}
