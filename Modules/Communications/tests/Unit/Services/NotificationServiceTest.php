<?php

namespace Modules\Communications\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;
use Modules\Communications\Services\NotificationService;
use Modules\Students\Models\Student;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected NotificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NotificationService();
    }

    public function test_list_returns_all_notifications(): void
    {
        Notification::create(['title' => 'Notification 1']);
        Notification::create(['title' => 'Notification 2']);

        $results = $this->service->list();

        $this->assertCount(2, $results);
    }

    public function test_create_creates_notification(): void
    {
        $notification = $this->service->create([
            'title' => 'Test Notification',
            'subtitle' => 'Subtitle',
            'body' => 'Body content',
        ]);

        $this->assertInstanceOf(Notification::class, $notification);
        $this->assertDatabaseHas('notifications', ['title' => 'Test Notification']);
    }

    public function test_find_returns_notification(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        $found = $this->service->find($notification->id);

        $this->assertEquals($notification->id, $found->id);
    }

    public function test_update_updates_notification(): void
    {
        $notification = Notification::create(['title' => 'Original']);

        $updated = $this->service->update($notification->id, ['title' => 'Updated']);

        $this->assertEquals('Updated', $updated->title);
    }

    public function test_delete_removes_notification(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        $result = $this->service->delete($notification->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    public function test_send_to_user_creates_notification_log(): void
    {
        $notification = Notification::create([
            'title' => 'Test',
            'subtitle' => 'Subtitle',
            'body' => 'Body',
        ]);
        $student = Student::factory()->create();

        $log = $this->service->sendToUser($notification, $student);

        $this->assertInstanceOf(NotificationLog::class, $log);
        $this->assertEquals($notification->id, $log->notification_id);
        $this->assertEquals(get_class($student), $log->notifiable_type);
        $this->assertEquals($student->id, $log->notifiable_id);
        $this->assertFalse($log->is_read);
    }

    public function test_send_to_multiple_creates_multiple_logs(): void
    {
        $notification = Notification::create(['title' => 'Test']);
        $students = Student::factory()->count(3)->create();

        $count = $this->service->sendToMultiple($notification, $students);

        $this->assertEquals(3, $count);
        $this->assertCount(3, NotificationLog::where('notification_id', $notification->id)->get());
    }

    public function test_mark_as_read_updates_log(): void
    {
        $notification = Notification::create(['title' => 'Test']);
        $student = Student::factory()->create();

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => false,
        ]);

        $result = $this->service->markAsRead($notification->id, $student);

        $this->assertTrue($result);
        $this->assertTrue(
            NotificationLog::where('notification_id', $notification->id)
                ->where('notifiable_id', $student->id)
                ->first()
                ->is_read
        );
    }

    public function test_get_unread_for_user_returns_unread_logs(): void
    {
        $notification1 = Notification::create(['title' => 'Test 1']);
        $notification2 = Notification::create(['title' => 'Test 2']);
        $student = Student::factory()->create();

        NotificationLog::create([
            'notification_id' => $notification1->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification2->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => true,
        ]);

        $results = $this->service->getUnreadForUser($student);

        $this->assertCount(1, $results);
    }

    public function test_get_unread_count_returns_correct_count(): void
    {
        $notification1 = Notification::create(['title' => 'Test 1']);
        $notification2 = Notification::create(['title' => 'Test 2']);
        $student = Student::factory()->create();

        NotificationLog::create([
            'notification_id' => $notification1->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification2->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => false,
        ]);

        $count = $this->service->getUnreadCount($student);

        $this->assertEquals(2, $count);
    }

    public function test_mark_all_as_read_updates_all_logs(): void
    {
        $notification1 = Notification::create(['title' => 'Test 1']);
        $notification2 = Notification::create(['title' => 'Test 2']);
        $student = Student::factory()->create();

        NotificationLog::create([
            'notification_id' => $notification1->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification2->id,
            'notifiable_type' => get_class($student),
            'notifiable_id' => $student->id,
            'is_read' => false,
        ]);

        $count = $this->service->markAllAsRead($student);

        $this->assertEquals(2, $count);
        $this->assertEquals(0, $this->service->getUnreadCount($student));
    }
}
