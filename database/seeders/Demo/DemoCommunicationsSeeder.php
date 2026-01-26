<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Communications\Models\Announcement;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;
use Modules\Students\Models\Student;

class DemoCommunicationsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Announcements
        $announcements = [
            [
                'title' => 'Welcome to the New SMS!',
                'content' => 'We are excited to launch the new Student Management System. Explore the new features!',
                'is_active' => true,
                'priority' => 10,
            ],
            [
                'title' => 'Mid-Term Exams Schedule',
                'content' => 'The mid-term exams will start from next Monday. Please check your schedule.',
                'is_active' => true,
                'priority' => 5,
            ],
            [
                'title' => 'Library Renovation',
                'content' => 'The main library will be closed for renovation this weekend.',
                'is_active' => true,
                'priority' => 1,
            ],
        ];

        foreach ($announcements as $data) {
            Announcement::firstOrCreate(
                ['title' => $data['title']],
                [
                    'details' => $data['content'],
                    'is_active' => $data['is_active'],
                ]
            );
        }

        // 2. Create Notifications
        $notifications = [
            [
                'title' => 'Assignment Due Reminder',
                'subtitle' => 'Math 101',
                'body' => 'Your assignment for Calculus I is due tomorrow at 11:59 PM.',
                'extra_data' => ['type' => 'assignment', 'course_id' => 1],
            ],
            [
                'title' => 'Campus Safety Alert',
                'subtitle' => 'Drill Tomorrow',
                'body' => 'There will be a fire drill tomorrow at 10 AM. Please follow staff instructions.',
                'extra_data' => ['type' => 'alert'],
            ],
            [
                'title' => 'New Grade Posted',
                'subtitle' => 'Physics 101',
                'body' => 'Your grade for the Lab Report 1 has been posted.',
                'extra_data' => ['type' => 'grade', 'course_id' => 2],
            ],
        ];

        foreach ($notifications as $data) {
            $notification = Notification::firstOrCreate(
                ['title' => $data['title'], 'body' => $data['body']],
                $data
            );

            if ($notification->wasRecentlyCreated) {

                // Create logs for a few random students
                $students = Student::inRandomOrder()->limit(5)->get();
                foreach ($students as $student) {
                    NotificationLog::create([
                        'notification_id' => $notification->id,
                        'notifiable_type' => Student::class,
                        'notifiable_id' => $student->id,
                        'is_read' => rand(0, 1),
                    ]);
                }
            }
        }
    }
}
