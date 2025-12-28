<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Demo Data for SMS-1...');

        // 1. Create Campuses
        $cairoCampus = Campus::firstOrCreate(
            ['code' => 'CAI'],
            [
                'name' => ['en' => 'Cairo Campus', 'ar' => 'فرع القاهرة'],
                'location' => 'Cairo, Egypt',
                'status' => 'active',
            ]
        );

        $alexCampus = Campus::firstOrCreate(
            ['code' => 'ALX'],
            [
                'name' => ['en' => 'Alexandria Campus', 'ar' => 'فرع الإسكندرية'],
                'location' => 'Alexandria, Egypt',
                'status' => 'active',
            ]
        );

        // 2. Create Faculties
        // 2. Create Faculties
        // 2. Create Faculties
        $engFaculty = Faculty::firstOrCreate(
            ['code' => 'ENG-CAI'],
            [
                'campus_id' => $cairoCampus->id,
                'name' => ['en' => 'Faculty of Engineering', 'ar' => 'كلية الهندسة'],
            ]
        );

        $sciFaculty = Faculty::firstOrCreate(
            ['code' => 'SCI-CAI'],
            [
                'campus_id' => $cairoCampus->id,
                'name' => ['en' => 'Faculty of Science', 'ar' => 'كلية العلوم'],
            ]
        );

        $medFaculty = Faculty::firstOrCreate(
            ['code' => 'MED-ALX'],
            [
                'campus_id' => $alexCampus->id,
                'name' => ['en' => 'Faculty of Medicine', 'ar' => 'كلية الطب'],
            ]
        );

        // 3. Create Users with Roles (assuming roles exist from PermissionsSeeder)
        
        // Dean of Engineering
        // Dean of Engineering
        $deanEng = User::firstOrCreate(
            ['email' => 'dean.eng@university.edu'],
            [
                'username' => 'dean.eng',
                'password' => Hash::make('secret'),
                'display_name' => 'Dean of Engineering',
                'first_name' => 'Ahmed',
                'last_name' => 'Ali',
                'is_admin' => false,
                'faculty_id' => $engFaculty->id,
                'role' => 'faculty_member',
            ]
        );
        // Assign Role (if using Spatie)
        // $deanEng->assignRole('Dean'); // Assuming 'Dean' role exists, if not 'Faculty Member'

        // Staff in Alexandria
        // Staff in Alexandria
        $staffAlex = User::firstOrCreate(
            ['email' => 'staff@alex.edu'],
            [
                'username' => 'staff.alex',
                'password' => Hash::make('secret'),
                'display_name' => 'Staff Alex',
                'first_name' => 'Mona',
                'last_name' => 'Said',
                'is_admin' => false,
                // 'faculty_id' => $medFaculty->id, // Optional
                'role' => 'staff',
            ]
        );

        $this->command->info('Demo Data Seeded: 2 Campuses, 3 Faculties, 2 Extra Users.');
    }
}
