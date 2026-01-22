<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\Demo\DemoStructureSeeder;
use Database\Seeders\Demo\DemoAcademicSeeder;
use Database\Seeders\Demo\DemoUserSeeder;
use Database\Seeders\Demo\DemoCourseSeeder;
use Database\Seeders\Demo\DemoServiceSeeder;
use Database\Seeders\Demo\DemoAppointmentSeeder;
use Database\Seeders\Demo\DemoTrainingSeeder;

/**
 * Demo data seeder with realistic, curated names for staging/demo environments.
 * Now split into manageable chunks in Demo/* seeders.
 */
class DemoDataSeeder extends Seeder
{
    /**
     * Curated list of realistic teacher surnames.
     */
    public const TEACHER_SURNAMES = [
        'Anderson',
        'Chen',
        'Williams',
        'Martinez',
        'Thompson',
        'Patel',
        'Johnson',
        'Kim',
        'Garcia',
        'Brown',
        'Wilson',
        'Taylor',
        'Lee',
        'Moore',
        'Jackson',
        'White',
        'Harris',
        'Clark',
        'Lewis',
        'Robinson',
        'Walker',
        'Young',
        'Allen',
        'King',
        'Wright',
        'Scott',
        'Green',
        'Baker',
        'Adams',
        'Nelson',
        'Hill',
        'Ramirez',
        'Campbell',
        'Mitchell',
        'Roberts',
        'Carter',
        'Phillips',
        'Evans',
        'Turner',
        'Torres',
        'Parker',
        'Collins',
        'Edwards',
        'Stewart',
        'Flores',
        'Morris',
        'Nguyen',
        'Murphy',
        'Rivera',
        'Cook',
    ];

    /**
     * Curated list of realistic student first names.
     */
    public const STUDENT_FIRST_NAMES = [
        'James',
        'Emma',
        'Oliver',
        'Sophia',
        'William',
        'Ava',
        'Benjamin',
        'Isabella',
        'Lucas',
        'Mia',
        'Henry',
        'Charlotte',
        'Alexander',
        'Amelia',
        'Sebastian',
        'Harper',
        'Jack',
        'Evelyn',
        'Aiden',
        'Abigail',
        'Owen',
        'Emily',
        'Samuel',
        'Elizabeth',
        'Ryan',
        'Sofia',
        'Nathan',
        'Avery',
        'Leo',
        'Ella',
        'Adam',
        'Scarlett',
        'Daniel',
        'Grace',
        'Matthew',
        'Chloe',
        'Joseph',
        'Victoria',
        'David',
        'Riley',
        'Andrew',
        'Aria',
        'Ethan',
        'Lily',
        'Michael',
        'Zoey',
        'Noah',
        'Hannah',
        'Liam',
        'Nora',
        'Omar',
        'Fatima',
        'Ahmed',
        'Layla',
        'Yusuf',
        'Sara',
        'Ali',
        'Nadia',
        'Hassan',
        'Mariam',
        'Wei',
        'Mei',
        'Jin',
        'Yuki',
        'Hiroshi',
        'Sakura',
        'Raj',
        'Priya',
        'Arun',
        'Ananya',
        'Carlos',
        'Maria',
        'Diego',
        'Ana',
        'Luis',
    ];

    /**
     * Curated list of realistic student last names.
     */
    public const STUDENT_LAST_NAMES = [
        'Smith',
        'Johnson',
        'Williams',
        'Brown',
        'Jones',
        'Garcia',
        'Miller',
        'Davis',
        'Rodriguez',
        'Martinez',
        'Hernandez',
        'Lopez',
        'Gonzalez',
        'Wilson',
        'Anderson',
        'Thomas',
        'Taylor',
        'Moore',
        'Jackson',
        'Martin',
        'Lee',
        'Perez',
        'Thompson',
        'White',
        'Harris',
        'Sanchez',
        'Clark',
        'Ramirez',
        'Lewis',
        'Robinson',
        'Walker',
        'Young',
        'Allen',
        'King',
        'Wright',
        'Scott',
        'Torres',
        'Nguyen',
        'Hill',
        'Flores',
        'Green',
        'Adams',
        'Nelson',
        'Baker',
        'Hall',
        'Rivera',
        'Campbell',
        'Mitchell',
        'Carter',
        'Roberts',
        'Ahmed',
        'Hassan',
        'Ali',
        'Khan',
        'Ibrahim',
        'Chen',
        'Wang',
        'Li',
        'Zhang',
        'Liu',
        'Patel',
        'Shah',
        'Kumar',
        'Singh',
        'Sharma',
        'Tanaka',
        'Yamamoto',
        'Suzuki',
        'Kim',
        'Park',
        'Santos',
        'Oliveira',
        'Silva',
        'Costa',
        'Ferreira',
    ];

    /**
     * Department-specific subject names.
     */
    public const SUBJECT_NAMES = [
        'CS' => ['Introduction to Programming', 'Data Structures and Algorithms', 'Database Management Systems', 'Computer Networks', 'Software Engineering', 'Operating Systems', 'Artificial Intelligence', 'Web Development', 'Computer Architecture', 'Cybersecurity Fundamentals'],
        'ME' => ['Engineering Mechanics', 'Thermodynamics', 'Fluid Mechanics', 'Machine Design', 'Manufacturing Processes', 'Heat Transfer', 'Control Systems', 'Materials Science', 'CAD/CAM Systems', 'Robotics Engineering'],
        'EE' => ['Circuit Analysis', 'Digital Electronics', 'Electromagnetic Theory', 'Power Systems', 'Signal Processing', 'Control Engineering', 'Microprocessors', 'Communication Systems', 'VLSI Design', 'Renewable Energy Systems'],
        'PHY' => ['Classical Mechanics', 'Electromagnetism', 'Quantum Physics', 'Thermodynamics and Statistical Mechanics', 'Optics', 'Nuclear Physics', 'Solid State Physics', 'Astrophysics', 'Mathematical Physics', 'Experimental Physics'],
        'MATH' => ['Calculus I', 'Linear Algebra', 'Differential Equations', 'Probability and Statistics', 'Abstract Algebra', 'Real Analysis', 'Complex Analysis', 'Numerical Methods', 'Discrete Mathematics', 'Topology'],
        'CHEM' => ['General Chemistry', 'Organic Chemistry', 'Inorganic Chemistry', 'Physical Chemistry', 'Analytical Chemistry', 'Biochemistry', 'Environmental Chemistry', 'Polymer Chemistry', 'Spectroscopy', 'Chemical Kinetics'],
        'GM' => ['Human Anatomy', 'Physiology', 'Biochemistry for Medicine', 'Pathology', 'Pharmacology', 'Microbiology', 'Clinical Diagnosis', 'Internal Medicine', 'Pediatrics', 'Emergency Medicine'],
        'SURG' => ['Surgical Anatomy', 'General Surgery', 'Anesthesiology', 'Orthopedic Surgery', 'Neurosurgery', 'Cardiovascular Surgery', 'Plastic Surgery', 'Trauma Surgery', 'Minimally Invasive Surgery', 'Surgical Oncology'],
        'ACC' => ['Financial Accounting', 'Managerial Accounting', 'Cost Accounting', 'Auditing', 'Taxation', 'Accounting Information Systems', 'Corporate Finance', 'International Accounting', 'Forensic Accounting', 'Government Accounting'],
        'MKT' => ['Principles of Marketing', 'Consumer Behavior', 'Digital Marketing', 'Brand Management', 'Marketing Research', 'Advertising and Promotion', 'Sales Management', 'International Marketing', 'Social Media Marketing', 'Retail Marketing'],
        'FIN' => ['Corporate Finance', 'Investment Analysis', 'Financial Markets', 'Portfolio Management', 'Risk Management', 'International Finance', 'Financial Modeling', 'Derivatives and Options', 'Banking and Financial Institutions', 'Behavioral Finance'],
        'FA' => ['Drawing Fundamentals', 'Color Theory', 'Painting Techniques', 'Sculpture', 'Art History', 'Digital Art', 'Printmaking', 'Mixed Media', 'Contemporary Art', 'Art Criticism'],
        'MUS' => ['Music Theory', 'Ear Training', 'Music History', 'Composition', 'Orchestration', 'Music Performance', 'Conducting', 'Ethnomusicology', 'Music Technology', 'Jazz Studies'],
        'PL' => ['Constitutional Law', 'Administrative Law', 'Criminal Law', 'International Law', 'Human Rights Law', 'Environmental Law', 'Tax Law', 'Labor Law', 'Immigration Law', 'Public Policy Law'],
        'PRL' => ['Contract Law', 'Property Law', 'Tort Law', 'Corporate Law', 'Family Law', 'Intellectual Property', 'Commercial Law', 'Banking Law', 'Insurance Law', 'Consumer Protection Law'],
    ];

    /**
     * Phone number patterns for realistic phone generation.
     */
    public const PHONE_PREFIXES = ['010', '011', '012', '015'];

    public function run(): void
    {
        if (App::isProduction()) {
            $this->command->warn('Skipping demo data in production environment.');
            return;
        }

        $this->call([
            PermissionsSeeder::class,
            DemoStructureSeeder::class,
            DemoAcademicSeeder::class,
            DemoUserSeeder::class,
            DemoCourseSeeder::class,
            DemoServiceSeeder::class,
            DemoAppointmentSeeder::class,
            DemoTrainingSeeder::class,
        ]);

        $this->command->info('Demo data seeded successfully!');
    }
}
