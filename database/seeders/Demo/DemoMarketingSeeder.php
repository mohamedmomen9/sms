<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Modules\Marketing\Models\Offer;
use Modules\Campus\Models\Campus;

class DemoMarketingSeeder extends Seeder
{
    public function run(): void
    {
        $campus = Campus::first();
        $campusId = $campus ? $campus->id : null;

        $offers = [
            [
                'title' => 'Student Laptop Discount',
                'description' => 'Get 10% off heavily on all MacBooks at the Campus Store.',
                'image_url' => 'https://example.com/images/macbook-offer.jpg',
                'discount_percentage' => 10,
                'valid_until' => now()->addMonths(2),
                'campus_id' => $campusId,
                'is_active' => true,
            ],
            [
                'title' => 'Cafeteria Meal Deal',
                'description' => 'Buy one lunch, get a free coffee every Friday!',
                'image_url' => 'https://example.com/images/coffee-offer.jpg',
                'discount_percentage' => 20,
                'valid_until' => now()->addMonths(1),
                'campus_id' => $campusId,
                'is_active' => true,
            ],
            [
                'title' => 'Gym Membership Promo',
                'description' => 'Sign up for the semester and get a free personal training session.',
                'image_url' => 'https://example.com/images/gym-offer.jpg',
                'discount_percentage' => 100,
                'valid_until' => now()->addWeeks(3),
                'campus_id' => null, // All campuses
                'is_active' => true,
            ],
        ];

        foreach ($offers as $data) {
            Offer::firstOrCreate(
                ['title' => $data['title']],
                [
                    'details' => $data['description'],
                    'image' => $data['image_url'],
                    'campus_id' => $data['campus_id'],
                    'is_active' => $data['is_active'],
                    'link' => null,
                ]
            );
        }
    }
}
