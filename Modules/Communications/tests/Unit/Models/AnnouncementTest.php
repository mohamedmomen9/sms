<?php

namespace Modules\Communications\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Communications\Models\Announcement;
use Modules\Campus\Models\Campus;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_announcement_can_be_created(): void
    {
        $announcement = Announcement::factory()->create([
            'title' => 'Test Announcement',
            'type' => 'news',
        ]);

        $this->assertDatabaseHas('announcements', [
            'title' => 'Test Announcement',
            'type' => 'news',
        ]);
    }

    public function test_announcement_belongs_to_campus(): void
    {
        $campus = Campus::factory()->create();
        $announcement = Announcement::factory()->create([
            'campus_id' => $campus->id,
        ]);

        $this->assertInstanceOf(Campus::class, $announcement->campus);
        $this->assertEquals($campus->id, $announcement->campus->id);
    }

    public function test_announcement_can_be_for_all_campuses(): void
    {
        $announcement = Announcement::factory()->create([
            'campus_id' => null,
        ]);

        $this->assertTrue($announcement->isForAllCampuses());
    }

    public function test_scope_for_campus_includes_all_campuses(): void
    {
        $campus = Campus::factory()->create();

        // Create announcement for specific campus
        Announcement::factory()->create([
            'campus_id' => $campus->id,
            'title' => 'Campus Specific',
        ]);

        // Create announcement for all campuses
        Announcement::factory()->create([
            'campus_id' => null,
            'title' => 'All Campuses',
        ]);

        // Create announcement for different campus
        $otherCampus = Campus::factory()->create();
        Announcement::factory()->create([
            'campus_id' => $otherCampus->id,
            'title' => 'Other Campus',
        ]);

        $results = Announcement::forCampus($campus->id)->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains('title', 'Campus Specific'));
        $this->assertTrue($results->contains('title', 'All Campuses'));
        $this->assertFalse($results->contains('title', 'Other Campus'));
    }

    public function test_scope_of_type_filters_correctly(): void
    {
        Announcement::factory()->create(['type' => 'news']);
        Announcement::factory()->create(['type' => 'events']);
        Announcement::factory()->create(['type' => 'lectures']);

        $results = Announcement::ofType('news')->get();

        $this->assertCount(1, $results);
        $this->assertEquals('news', $results->first()->type);
    }

    public function test_scope_of_types_filters_multiple_types(): void
    {
        Announcement::factory()->create(['type' => 'news']);
        Announcement::factory()->create(['type' => 'events']);
        Announcement::factory()->create(['type' => 'lectures']);
        Announcement::factory()->create(['type' => 'announcements']);

        $results = Announcement::ofTypes(['news', 'events'])->get();

        $this->assertCount(2, $results);
    }

    public function test_scope_active_filters_active_announcements(): void
    {
        Announcement::factory()->create(['is_active' => true]);
        Announcement::factory()->create(['is_active' => false]);

        $results = Announcement::active()->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->first()->is_active);
    }

    public function test_scope_search_finds_by_title(): void
    {
        Announcement::factory()->create(['title' => 'Welcome to Campus']);
        Announcement::factory()->create(['title' => 'Important Notice']);

        $results = Announcement::search('Welcome')->get();

        $this->assertCount(1, $results);
        $this->assertStringContainsString('Welcome', $results->first()->title);
    }

    public function test_scope_search_finds_by_details(): void
    {
        Announcement::factory()->create([
            'title' => 'Event',
            'details' => 'This is a special announcement for students',
        ]);
        Announcement::factory()->create([
            'title' => 'Notice',
            'details' => 'General information',
        ]);

        $results = Announcement::search('special')->get();

        $this->assertCount(1, $results);
    }

    public function test_get_types_returns_all_valid_types(): void
    {
        $types = Announcement::getTypes();

        $this->assertContains('news', $types);
        $this->assertContains('events', $types);
        $this->assertContains('lectures', $types);
        $this->assertContains('announcements', $types);
        $this->assertCount(4, $types);
    }
}
