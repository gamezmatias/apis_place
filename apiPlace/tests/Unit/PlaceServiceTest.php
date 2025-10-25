<?php

use App\Models\Place;
use App\Services\PlaceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('PlaceService', function () {
    describe('list method', function () {
        it('returns paginated places', function () {
            Place::factory()->count(20)->create();
            $service = new PlaceService();

            $result = $service->list();

            expect($result)->toBeInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class);
            expect($result->total())->toBe(20);
            expect($result->perPage())->toBe(15); // default per page
        });

        it('filters places by name', function () {
            Place::factory()->create(['name' => 'Beautiful Park']);
            Place::factory()->create(['name' => 'Amazing Beach']);
            Place::factory()->create(['name' => 'Historic Museum']);
            $service = new PlaceService();

            $result = $service->list('park');

            expect($result->total())->toBe(1);
            expect($result->items()[0]->name)->toBe('Beautiful Park');
        });

        it('respects per page parameter', function () {
            Place::factory()->count(20)->create();
            $service = new PlaceService();

            $result = $service->list(null, 5);

            expect($result->perPage())->toBe(5);
            expect(count($result->items()))->toBe(5);
        });

        it('orders results by name', function () {
            Place::factory()->create(['name' => 'Zebra Park']);
            Place::factory()->create(['name' => 'Alpha Beach']);
            Place::factory()->create(['name' => 'Beta Museum']);
            $service = new PlaceService();

            $result = $service->list();

            $items = collect($result->items());
            $names = $items->pluck('name')->toArray();
            expect($names)->toBe(['Alpha Beach', 'Beta Museum', 'Zebra Park']);
        });
    });

    describe('create method', function () {
        it('creates a place with auto-generated slug', function () {
            $data = [
                'name' => 'Central Park',
                'city' => 'New York',
                'state' => 'NY'
            ];
            $service = new PlaceService();

            $place = $service->create($data);

            expect($place)->toBeInstanceOf(Place::class);
            expect($place->name)->toBe('Central Park');
            expect($place->slug)->toBe('central-park');
            expect($place->city)->toBe('New York');
            expect($place->state)->toBe('NY');
        });

        it('creates a place with custom slug', function () {
            $data = [
                'name' => 'Central Park',
                'slug' => 'custom-park-slug',
                'city' => 'New York',
                'state' => 'NY'
            ];
            $service = new PlaceService();

            $place = $service->create($data);

            expect($place->slug)->toBe('custom-park-slug');
        });
    });

    describe('update method', function () {
        it('updates place and generates new slug when name changes', function () {
            $place = Place::factory()->create([
                'name' => 'Old Name',
                'slug' => 'old-name'
            ]);
            $service = new PlaceService();

            $updatedPlace = $service->update($place, [
                'name' => 'New Name'
            ]);

            expect($updatedPlace->name)->toBe('New Name');
            expect($updatedPlace->slug)->toBe('new-name');
        });

        it('keeps existing slug when name changes but slug is provided', function () {
            $place = Place::factory()->create([
                'name' => 'Old Name',
                'slug' => 'old-name'
            ]);
            $service = new PlaceService();

            $updatedPlace = $service->update($place, [
                'name' => 'New Name',
                'slug' => 'custom-slug'
            ]);

            expect($updatedPlace->name)->toBe('New Name');
            expect($updatedPlace->slug)->toBe('custom-slug');
        });

        it('updates only provided fields', function () {
            $place = Place::factory()->create([
                'name' => 'Original Name',
                'city' => 'Original City',
                'state' => 'Original State'
            ]);
            $service = new PlaceService();

            $updatedPlace = $service->update($place, [
                'city' => 'New City'
            ]);

            expect($updatedPlace->name)->toBe('Original Name');
            expect($updatedPlace->city)->toBe('New City');
            expect($updatedPlace->state)->toBe('Original State');
        });
    });

    describe('delete method', function () {
        it('deletes a place', function () {
            $place = Place::factory()->create();
            $service = new PlaceService();

            $result = $service->delete($place);

            expect($result)->toBeTrue();
            expect(Place::find($place->id))->toBeNull();
        });
    });
});