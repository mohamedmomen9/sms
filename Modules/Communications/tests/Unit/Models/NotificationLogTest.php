<?php

namespace Modules\Communications\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;

class NotificationLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_log_can_be_created(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        $log = NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'title' => 'Test Notification',
            'is_read' => false,
        ]);

        $this->assertDatabaseHas('notification_logs', [
            'notification_id' => $notification->id,
            'is_read' => false,
        ]);
    }

    public function test_notification_log_belongs_to_notification(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        $log = NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => false,
        ]);

        $this->assertInstanceOf(Notification::class, $log->notification);
        $this->assertEquals($notification->id, $log->notification->id);
    }

    public function test_scope_unread_filters_unread_logs(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 2,
            'is_read' => true,
        ]);

        $results = NotificationLog::unread()->get();

        $this->assertCount(1, $results);
        $this->assertFalse($results->first()->is_read);
    }

    public function test_scope_read_filters_read_logs(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 2,
            'is_read' => true,
        ]);

        $results = NotificationLog::read()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is_read);
    }

    public function test_scope_for_notifiable_filters_by_type_and_id(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Teachers\Models\Teacher',
            'notifiable_id' => 1,
            'is_read' => false,
        ]);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 2,
            'is_read' => false,
        ]);

        $results = NotificationLog::forNotifiable('Modules\Students\Models\Student', 1)->get();

        $this->assertCount(1, $results);
    }

    public function test_mark_as_read_updates_is_read_to_true(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        $log = NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => false,
        ]);

        $log->markAsRead();

        $this->assertTrue($log->fresh()->is_read);
    }

    public function test_mark_as_unread_updates_is_read_to_false(): void
    {
        $notification = Notification::create(['title' => 'Test']);

        $log = NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => true,
        ]);

        $log->markAsUnread();

        $this->assertFalse($log->fresh()->is_read);
    }
}
