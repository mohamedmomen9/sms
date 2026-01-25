<?php

namespace Modules\Admissions\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Modules\Admissions\Models\Applicant;
use Modules\Students\Models\Student;

class ApplicantService
{
    public function create(array $data): Applicant
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return Applicant::create($data);
    }

    public function update(int $id, array $data): Applicant
    {
        $applicant = Applicant::findOrFail($id);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $applicant->update($data);
        return $applicant;
    }

    public function delete(int $id): bool
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return false;
        }
        return $applicant->delete();
    }

    public function changeStatus(int $id, string $status): Applicant
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update(['status' => $status]);
        return $applicant;
    }

    public function convertToStudent(int $id): Student
    {
        $applicant = Applicant::findOrFail($id);

        if ($applicant->status !== 'accepted') {
            throw new Exception("Applicant must be accepted before converting to student");
        }

        // Logic to create student from applicant
        $randomPass = Str::random(10);

        $student = Student::create([
            'name' => $applicant->name,
            'email' => $applicant->email,
            'phone' => $applicant->phone,
            'password' => Hash::make($randomPass),
            // campus_id needs to be inferred
        ]);

        return $student;
    }

    public function authenticate(string $phone, string $password): ?Applicant
    {
        $applicant = Applicant::where('phone', $phone)->first();
        if (!$applicant || !Hash::check($password, $applicant->password)) {
            return null;
        }
        return $applicant;
    }
}
