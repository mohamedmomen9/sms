<?php

namespace Modules\Communications\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communications\Models\Announcement;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'details' => fake()->paragraph(3),
            'campus_id' => null,
            'link' => fake()->optional(0.3)->url(),
            'date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'type' => fake()->randomElement(['news', 'events', 'lectures', 'announcements']),
            'is_active' => true,
            'image' => null,
            'cropped_image' => null,
        ];
    }

    /**
     * Indicate the announcement is for a specific campus
     */
    public function forCampus(int $campusId): static
    {
        return $this->state(fn(array $attributes) => [
            'campus_id' => $campusId,
        ]);
    }

    /**
     * Indicate the announcement is inactive
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate the announcement type
     */
    public function ofType(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Create a news announcement
     */
    public function news(): static
    {
        return $this->ofType('news');
    }

    /**
     * Create an events announcement
     */
    public function events(): static
    {
        return $this->ofType('events');
    }

    /**
     * Create a lectures announcement
     */
    public function lectures(): static
    {
        return $this->ofType('lectures');
    }
}
