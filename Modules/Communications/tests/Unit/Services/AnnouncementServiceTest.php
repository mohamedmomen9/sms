<?php

namespace Modules\Communications\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Communications\Models\Announcement;
use Modules\Communications\Services\AnnouncementService;
use Modules\Campus\Models\Campus;

class AnnouncementServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AnnouncementService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AnnouncementService();
    }

    public function test_list_returns_all_announcements(): void
    {
        Announcement::factory()->count(3)->create();

        $results = $this->service->list();

        $this->assertCount(3, $results);
    }

    public function test_list_filters_by_campus(): void
    {
        $campus = Campus::factory()->create();
        Announcement::factory()->create(['campus_id' => $campus->id]);
        Announcement::factory()->create(['campus_id' => null]);
        Announcement::factory()->create(['campus_id' => Campus::factory()->create()->id]);

        $results = $this->service->list(['campus_id' => $campus->id]);

        $this->assertCount(2, $results);
    }

    public function test_list_filters_by_type(): void
    {
        Announcement::factory()->create(['type' => 'news']);
        Announcement::factory()->create(['type' => 'events']);

        $results = $this->service->list(['type' => 'news']);

        $this->assertCount(1, $results);
    }

    public function test_list_filters_by_multiple_types(): void
    {
        Announcement::factory()->create(['type' => 'news']);
        Announcement::factory()->create(['type' => 'events']);
        Announcement::factory()->create(['type' => 'lectures']);

        $results = $this->service->list(['types' => ['news', 'events']]);

        $this->assertCount(2, $results);
    }

    public function test_list_filters_active_only(): void
    {
        Announcement::factory()->create(['is_active' => true]);
        Announcement::factory()->create(['is_active' => false]);

        $results = $this->service->list(['active_only' => true]);

        $this->assertCount(1, $results);
    }

    public function test_create_creates_new_announcement(): void
    {
        $data = [
            'title' => 'New Announcement',
            'type' => 'news',
            'date' => now()->toDateString(),
        ];

        $announcement = $this->service->create($data);

        $this->assertInstanceOf(Announcement::class, $announcement);
        $this->assertEquals('New Announcement', $announcement->title);
        $this->assertDatabaseHas('announcements', ['title' => 'New Announcement']);
    }

    public function test_find_returns_announcement_by_id(): void
    {
        $announcement = Announcement::factory()->create();

        $found = $this->service->find($announcement->id);

        $this->assertEquals($announcement->id, $found->id);
    }

    public function test_find_returns_null_for_invalid_id(): void
    {
        $found = $this->service->find(999);

        $this->assertNull($found);
    }

    public function test_update_updates_announcement(): void
    {
        $announcement = Announcement::factory()->create(['title' => 'Original']);

        $updated = $this->service->update($announcement->id, ['title' => 'Updated']);

        $this->assertEquals('Updated', $updated->title);
        $this->assertDatabaseHas('announcements', ['title' => 'Updated']);
    }

    public function test_update_returns_null_for_invalid_id(): void
    {
        $result = $this->service->update(999, ['title' => 'Updated']);

        $this->assertNull($result);
    }

    public function test_delete_removes_announcement(): void
    {
        $announcement = Announcement::factory()->create();

        $result = $this->service->delete($announcement->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }

    public function test_delete_returns_false_for_invalid_id(): void
    {
        $result = $this->service->delete(999);

        $this->assertFalse($result);
    }

    public function test_search_finds_announcements(): void
    {
        Announcement::factory()->create([
            'title' => 'Important Campus Event',
            'is_active' => true,
        ]);
        Announcement::factory()->create([
            'title' => 'Weekly Newsletter',
            'is_active' => true,
        ]);

        $results = $this->service->search('Campus');

        $this->assertCount(1, $results);
    }

    public function test_get_for_campus_includes_all_campuses(): void
    {
        $campus = Campus::factory()->create();
        Announcement::factory()->create([
            'campus_id' => $campus->id,
            'is_active' => true,
        ]);
        Announcement::factory()->create([
            'campus_id' => null,
            'is_active' => true,
        ]);

        $results = $this->service->getForCampus($campus->id);

        $this->assertCount(2, $results);
    }

    public function test_search_events_returns_only_events_type(): void
    {
        $campus = Campus::factory()->create();
        Announcement::factory()->create([
            'campus_id' => $campus->id,
            'type' => 'events',
            'title' => 'Concert Event',
            'is_active' => true,
        ]);
        Announcement::factory()->create([
            'campus_id' => $campus->id,
            'type' => 'news',
            'title' => 'Concert News',
            'is_active' => true,
        ]);

        $results = $this->service->searchEvents($campus->id, 'Concert');

        $this->assertCount(1, $results);
        $this->assertEquals('events', $results->first()->type);
    }
}
