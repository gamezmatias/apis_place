<?php

namespace App\Services;

use App\Models\Place;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PlaceService
{
    public function list(?string $name = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Place::query();

        if ($name) {
            // Use ILIKE for PostgreSQL and LIKE for SQLite (case-insensitive for both)
            if (config('database.default') === 'pgsql') {
                $query->where('name', 'ILIKE', "%{$name}%");
            } else {
                $query->whereRaw('LOWER(name) LIKE LOWER(?)', ["%{$name}%"]);
            }
        }

        return $query->orderBy('name')
                    ->paginate($perPage);
    }

    public function create(array $data): Place
    {
        // Generate slug from name if not provided
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return Place::create($data);
    }

    public function update(Place $place, array $data): Place
    {
        // Generate slug from name if name is being updated and slug is not provided
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $place->update($data);
        
        return $place->fresh();
    }

    public function delete(Place $place): bool
    {
        return $place->delete();
    }
}