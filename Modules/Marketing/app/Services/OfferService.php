<?php

namespace Modules\Marketing\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Marketing\Models\Offer;
use Modules\Marketing\Models\OfferLog;
use InvalidArgumentException;

class OfferService
{
    public function list(array $filters = []): Collection
    {
        $query = Offer::query();

        if (isset($filters['campus_id'])) {
            $query->forCampus($filters['campus_id']);
        }

        if (isset($filters['active_only']) && $filters['active_only']) {
            $query->active();
        }

        return $query->latest()->get();
    }

    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    public function update(int $id, array $data): Offer
    {
        $offer = Offer::findOrFail($id);
        $offer->update($data);
        return $offer;
    }

    public function delete(int $id): bool
    {
        $offer = Offer::find($id);
        if (!$offer) {
            return false;
        }
        return $offer->delete();
    }

    public function toggleFavorite(int $offerId, Model $entity): bool
    {
        $offer = Offer::findOrFail($offerId);
        $type = $offer->mapModelToEntityType($entity);

        if (! $type) {
            throw new InvalidArgumentException("Invalid entity type");
        }

        $log = OfferLog::where('offer_id', $offerId)
            ->where('entity_type', $type)
            ->where('entity_id', $entity->id)
            ->first();

        if ($log) {
            $log->is_favorite = ! $log->is_favorite;
            $log->save();
            return $log->is_favorite;
        }

        OfferLog::create([
            'offer_id' => $offerId,
            'entity_type' => $type,
            'entity_id' => $entity->id,
            'is_favorite' => true,
        ]);
        return true;
    }

    public function getFavoritesForUser(Model $user): Collection
    {
        // Simple mapping based on class name
        $class = get_class($user);
        $type = null;
        if (str_contains($class, 'Student')) {
            $type = 'student';
        }
        if (str_contains($class, 'Teacher')) {
            $type = 'teacher';
        }
        if (str_contains($class, 'Guardian')) {
            $type = 'parent';
        }

        if (! $type) {
            return new Collection();
        }

        return Offer::whereHas('logs', function ($query) use ($type, $user) {
            $query->where('entity_type', $type)
                ->where('entity_id', $user->id)
                ->where('is_favorite', true);
        })->get();
    }

    public function getAnalytics(int $offerId): array
    {
        $logs = OfferLog::where('offer_id', $offerId)->get();

        return [
            'total_favorites' => $logs->where('is_favorite', true)->count(),
            'by_type' => [
                'student' => $logs->where('entity_type', 'student')->where('is_favorite', true)->count(),
                'teacher' => $logs->where('entity_type', 'teacher')->where('is_favorite', true)->count(),
                'parent' => $logs->where('entity_type', 'parent')->where('is_favorite', true)->count(),
            ],
        ];
    }
}
