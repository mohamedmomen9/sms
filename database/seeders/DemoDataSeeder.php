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
        $cairoCampus = Campus::create([
            'code' => 'CAI',
            'name' => 'Cairo Campus',
            'name_en' => 'Cairo Campus',
            'name_ar' => 'فرع القاهرة',
            'location' => 'Cairo, Egypt',
            'status' => 'active',
        ]);

        $alexCampus = Campus::create([
            'code' => 'ALX',
            'name' => 'Alexandria Campus',
            'name_en' => 'Alexandria Campus',
            'name_ar' => 'فرع الإسكندرية',
            'location' => 'Alexandria, Egypt',
            'status' => 'active',
        ]);

        // 2. Create Faculties
        $engFaculty = Faculty::create([
            'campus_id' => $cairoCampus->id,
            'code' => 'ENG-CAI',
            'name' => 'Faculty of Engineering (Cairo)',
            'name_en' => 'Faculty of Engineering',
            'name_ar' => 'كلية الهندسة',
        ]);

        $sciFaculty = Faculty::create([
            'campus_id' => $cairoCampus->id,
            'code' => 'SCI-CAI',
            'name' => 'Faculty of Science (Cairo)',
            'name_en' => 'Faculty of Science',
            'name_ar' => 'كلية العلوم',
        ]);

        $medFaculty = Faculty::create([
            'campus_id' => $alexCampus->id,
            'code' => 'MED-ALX',
            'name' => 'Faculty of Medicine (Alexandria)',
            'name_en' => 'Faculty of Medicine',
            'name_ar' => 'كلية الطب',
        ]);

        // 3. Create Users with Roles (assuming roles exist from PermissionsSeeder)
        
        // Dean of Engineering
        $deanEng = User::create([
            'username' => 'dean.eng',
            'email' => 'dean.eng@university.edu',
            'password' => Hash::make('secret'),
            'display_name' => 'Dean of Engineering',
            'first_name' => 'Ahmed',
            'last_name' => 'Ali',
            'is_admin' => false,
            'faculty_id' => $engFaculty->id,
            'role' => 'faculty_member',
        ]);
        // Assign Role (if using Spatie)
        // $deanEng->assignRole('Dean'); // Assuming 'Dean' role exists, if not 'Faculty Member'

        // Staff in Alexandria
        $staffAlex = User::create([
            'username' => 'staff.alex',
            'email' => 'staff@alex.edu',
            'password' => Hash::make('secret'),
            'display_name' => 'Staff Alex',
            'first_name' => 'Mona',
            'last_name' => 'Said',
            'is_admin' => false,
            // 'faculty_id' => $medFaculty->id, // Optional: Staff might belong to a faculty or just campus (but user model aligns to faculty currently)
            'role' => 'staff',
        ]);

        $this->command->info('Demo Data Seeded: 2 Campuses, 3 Faculties, 2 Extra Users.');
    }
}
