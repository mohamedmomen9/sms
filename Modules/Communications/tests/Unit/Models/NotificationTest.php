<?php

namespace Modules\Communications\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Communications\Models\Notification;
use Modules\Communications\Models\NotificationLog;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_can_be_created(): void
    {
        $notification = Notification::create([
            'title' => 'Test Notification',
            'subtitle' => 'Test Subtitle',
            'body' => 'This is the body content',
        ]);

        $this->assertDatabaseHas('notifications', [
            'title' => 'Test Notification',
        ]);
    }

    public function test_notification_has_many_logs(): void
    {
        $notification = Notification::create([
            'title' => 'Test Notification',
        ]);

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
            'is_read' => false,
        ]);

        $this->assertCount(2, $notification->logs);
    }

    public function test_notification_extra_data_is_cast_to_array(): void
    {
        $notification = Notification::create([
            'title' => 'Test Notification',
            'extra_data' => ['screen' => 'dashboard', 'id' => 123],
        ]);

        $this->assertIsArray($notification->extra_data);
        $this->assertEquals('dashboard', $notification->extra_data['screen']);
    }

    public function test_scope_search_finds_by_title(): void
    {
        Notification::create(['title' => 'Important Update']);
        Notification::create(['title' => 'Weekly Newsletter']);

        $results = Notification::search('Important')->get();

        $this->assertCount(1, $results);
    }

    public function test_scope_search_finds_by_body(): void
    {
        Notification::create([
            'title' => 'Notice',
            'body' => 'Please check the exam schedule',
        ]);
        Notification::create([
            'title' => 'Update',
            'body' => 'General update',
        ]);

        $results = Notification::search('exam')->get();

        $this->assertCount(1, $results);
    }

    public function test_unread_count_attribute(): void
    {
        $notification = Notification::create([
            'title' => 'Test Notification',
        ]);

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

        $this->assertEquals(1, $notification->unread_count);
    }

    public function test_read_count_attribute(): void
    {
        $notification = Notification::create([
            'title' => 'Test Notification',
        ]);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 1,
            'is_read' => true,
        ]);

        NotificationLog::create([
            'notification_id' => $notification->id,
            'notifiable_type' => 'Modules\Students\Models\Student',
            'notifiable_id' => 2,
            'is_read' => true,
        ]);

        $this->assertEquals(2, $notification->read_count);
    }
}
