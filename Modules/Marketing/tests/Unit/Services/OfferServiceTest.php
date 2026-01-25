<?php

namespace Modules\Marketing\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Marketing\Models\Offer;
use Modules\Marketing\Models\OfferLog;
use Modules\Marketing\Services\OfferService;

class OfferServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OfferService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OfferService();
    }

    public function test_create_creates_offer(): void
    {
        $offer = $this->service->create([
            'title' => 'New Offer',
            'image' => 'offer.jpg',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('offers', ['title' => 'New Offer']);
    }

    public function test_toggle_favorite_toggles_status(): void
    {
        $offer = Offer::create(['title' => 'Offer', 'image' => 'img.jpg']);
        $student = \Modules\Students\Models\Student::factory()->create();

        // First toggle: Favorite (create log)
        $result = $this->service->toggleFavorite($offer->id, $student);
        $this->assertTrue($result);
        $this->assertDatabaseHas('offer_logs', [
            'offer_id' => $offer->id,
            'entity_id' => $student->id,
            'is_favorite' => true
        ]);

        // Second toggle: Unfavorite (update log)
        $result = $this->service->toggleFavorite($offer->id, $student);
        $this->assertFalse($result);
        $this->assertDatabaseHas('offer_logs', [
            'offer_id' => $offer->id,
            'entity_id' => $student->id,
            'is_favorite' => false
        ]);
    }

    public function test_get_analytics_returns_counts(): void
    {
        $offer = Offer::create(['title' => 'Offer', 'image' => 'img.jpg']);

        OfferLog::create(['offer_id' => $offer->id, 'entity_type' => 'student', 'entity_id' => 1, 'is_favorite' => true]);
        OfferLog::create(['offer_id' => $offer->id, 'entity_type' => 'student', 'entity_id' => 2, 'is_favorite' => false]);
        OfferLog::create(['offer_id' => $offer->id, 'entity_type' => 'teacher', 'entity_id' => 1, 'is_favorite' => true]);

        $stats = $this->service->getAnalytics($offer->id);

        $this->assertEquals(2, $stats['total_favorites']);
        $this->assertEquals(1, $stats['by_type']['student']);
        $this->assertEquals(1, $stats['by_type']['teacher']);
    }
}
