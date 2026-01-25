<?php

namespace Modules\Communications\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Communications\Models\Announcement;

class AnnouncementService
{
    /**
     * Get announcements with optional filters
     */
    public function list(array $filters = []): Collection
    {
        $query = Announcement::query();

        if (isset($filters['campus_id'])) {
            $query->forCampus($filters['campus_id']);
        }

        if (isset($filters['types']) && is_array($filters['types'])) {
            $query->ofTypes($filters['types']);
        } elseif (isset($filters['type'])) {
            $query->ofType($filters['type']);
        }

        if (isset($filters['active_only']) && $filters['active_only']) {
            $query->active();
        }

        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->latest()->get();
    }

    /**
     * Get all announcements (no filters)
     */
    public function getAll(): Collection
    {
        return Announcement::latest()->get();
    }

    /**
     * Find an announcement by ID
     */
    public function find(int $id): ?Announcement
    {
        return Announcement::find($id);
    }

    /**
     * Create a new announcement
     */
    public function create(array $data): Announcement
    {
        return Announcement::create($data);
    }

    /**
     * Update an existing announcement
     */
    public function update(int $id, array $data): ?Announcement
    {
        $announcement = $this->find($id);

        if (!$announcement) {
            return null;
        }

        $announcement->update($data);

        return $announcement->fresh();
    }

    /**
     * Delete an announcement
     */
    public function delete(int $id): bool
    {
        $announcement = $this->find($id);

        if (!$announcement) {
            return false;
        }

        return $announcement->delete();
    }

    /**
     * Search announcements
     */
    public function search(string $query, ?int $campusId = null): Collection
    {
        return Announcement::query()
            ->forCampus($campusId)
            ->search($query)
            ->active()
            ->latest()
            ->get();
    }

    /**
     * Get announcements for a specific campus (including "All Campuses")
     */
    public function getForCampus(int $campusId, array $types = []): Collection
    {
        $query = Announcement::query()
            ->forCampus($campusId)
            ->active();

        if (!empty($types)) {
            $query->ofTypes($types);
        }

        return $query->latest()->get();
    }

    /**
     * Get events (type = 'events') for a campus
     */
    public function searchEvents(int $campusId, string $searchQuery): Collection
    {
        return Announcement::query()
            ->forCampus($campusId)
            ->ofType('events')
            ->search($searchQuery)
            ->active()
            ->latest()
            ->get();
    }
}
