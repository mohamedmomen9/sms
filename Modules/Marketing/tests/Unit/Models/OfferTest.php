<?php

namespace Modules\Marketing\Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Marketing\Models\Offer;
use Modules\Marketing\Models\OfferLog;
use Modules\Campus\Models\Campus;

class OfferTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_active_filters_active_offers(): void
    {
        Offer::create(['title' => 'Active', 'is_active' => true, 'image' => 'img.jpg']);
        Offer::create(['title' => 'Inactive', 'is_active' => false, 'image' => 'img.jpg']);

        $results = Offer::active()->get();

        $this->assertCount(1, $results);
        $this->assertEquals('Active', $results->first()->title);
    }

    public function test_scope_for_campus_filters_correctly(): void
    {
        $campus = Campus::factory()->create();
        Offer::create(['title' => 'Specific', 'campus_id' => $campus->id, 'image' => 'img.jpg']);
        Offer::create(['title' => 'All', 'campus_id' => null, 'image' => 'img.jpg']);
        $otherCampus = Campus::factory()->create();
        Offer::create(['title' => 'Other', 'campus_id' => $otherCampus->id, 'image' => 'img.jpg']);

        $results = Offer::forCampus($campus->id)->get();

        $this->assertCount(2, $results);
        $this->assertTrue($results->contains('title', 'Specific'));
        $this->assertTrue($results->contains('title', 'All'));
    }

    public function test_is_favorited_by_checks_logs(): void
    {
        $offer = Offer::create(['title' => 'Test', 'image' => 'img.jpg']);
        // Mock a student model since we don't assume Student factory here or we can use it if available.
        // Let's use generic model behavior mock or create actual models if factories available.
        // Assuming Student factory exists from previous stages.
        $student = \Modules\Students\Models\Student::factory()->create();

        OfferLog::create([
            'offer_id' => $offer->id,
            'entity_type' => 'student',
            'entity_id' => $student->id,
            'is_favorite' => true,
        ]);

        $this->assertTrue($offer->isFavoritedBy($student));
    }
}
