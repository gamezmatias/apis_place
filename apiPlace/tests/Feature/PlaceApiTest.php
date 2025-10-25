<?php

use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Places API', function () {

    describe('GET /api/places', function () {
        it('returns empty list when no places exist', function () {
            $response = $this->getJson('/api/places');

            $response->assertStatus(200)
                    ->assertJsonStructure([
                        'data',
                        'current_page',
                        'per_page',
                        'total'
                    ])
                    ->assertJsonCount(0, 'data');
        });

        it('returns paginated list of places', function () {
            Place::factory()->count(20)->create();

            $response = $this->getJson('/api/places');

            $response->assertStatus(200)
                    ->assertJsonStructure([
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                                'slug',
                                'city',
                                'state',
                                'created_at',
                                'updated_at'
                            ]
                        ],
                        'current_page',
                        'per_page',
                        'total'
                    ])
                    ->assertJsonCount(15, 'data'); // Default per_page is 15
        });

        it('filters places by name', function () {
            Place::factory()->create(['name' => 'Beautiful Park']);
            Place::factory()->create(['name' => 'Amazing Beach']);
            Place::factory()->create(['name' => 'Historic Museum']);

            $response = $this->getJson('/api/places?name=park');

            $response->assertStatus(200)
                    ->assertJsonCount(1, 'data')
                    ->assertJsonPath('data.0.name', 'Beautiful Park');
        });

        it('accepts per_page parameter', function () {
            Place::factory()->count(20)->create();

            $response = $this->getJson('/api/places?per_page=5');

            $response->assertStatus(200)
                    ->assertJsonCount(5, 'data')
                    ->assertJsonPath('per_page', 5);
        });
    });

    describe('POST /api/places', function () {
        it('creates a new place', function () {
            $placeData = [
                'name' => 'Central Park',
                'city' => 'New York',
                'state' => 'NY'
            ];

            $response = $this->postJson('/api/places', $placeData);

            $response->assertStatus(201)
                    ->assertJsonStructure([
                        'data' => [
                            'id',
                            'name',
                            'slug',
                            'city',
                            'state',
                            'created_at',
                            'updated_at'
                        ]
                    ])
                    ->assertJsonPath('data.name', 'Central Park')
                    ->assertJsonPath('data.slug', 'central-park')
                    ->assertJsonPath('data.city', 'New York')
                    ->assertJsonPath('data.state', 'NY');

            $this->assertDatabaseHas('places', [
                'name' => 'Central Park',
                'city' => 'New York',
                'state' => 'NY'
            ]);
        });

        it('creates place with custom slug', function () {
            $placeData = [
                'name' => 'Central Park',
                'slug' => 'custom-park-slug',
                'city' => 'New York',
                'state' => 'NY'
            ];

            $response = $this->postJson('/api/places', $placeData);

            $response->assertStatus(201)
                    ->assertJsonPath('data.slug', 'custom-park-slug');
        });

        it('validates required fields', function () {
            $response = $this->postJson('/api/places', []);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['name', 'city', 'state']);
        });

        it('validates unique name', function () {
            Place::factory()->create(['name' => 'Central Park']);

            $response = $this->postJson('/api/places', [
                'name' => 'Central Park',
                'city' => 'New York',
                'state' => 'NY'
            ]);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['name']);
        });
    });

    describe('GET /api/places/{id}', function () {
        it('returns a specific place', function () {
            $place = Place::factory()->create();

            $response = $this->getJson("/api/places/{$place->id}");

            $response->assertStatus(200)
                    ->assertJsonStructure([
                        'data' => [
                            'id',
                            'name',
                            'slug',
                            'city',
                            'state',
                            'created_at',
                            'updated_at'
                        ]
                    ])
                    ->assertJsonPath('data.id', $place->id)
                    ->assertJsonPath('data.name', $place->name);
        });

        it('returns 404 for non-existent place', function () {
            $response = $this->getJson('/api/places/999');

            $response->assertStatus(404);
        });
    });

    describe('PUT /api/places/{id}', function () {
        it('updates a place', function () {
            $place = Place::factory()->create([
                'name' => 'Old Name',
                'city' => 'Old City'
            ]);

            $updateData = [
                'name' => 'New Name',
                'city' => 'New City'
            ];

            $response = $this->putJson("/api/places/{$place->id}", $updateData);

            $response->assertStatus(200)
                    ->assertJsonPath('data.name', 'New Name')
                    ->assertJsonPath('data.city', 'New City')
                    ->assertJsonPath('data.slug', 'new-name'); // Auto-generated slug

            $this->assertDatabaseHas('places', [
                'id' => $place->id,
                'name' => 'New Name',
                'city' => 'New City'
            ]);
        });

        it('updates only provided fields', function () {
            $place = Place::factory()->create([
                'name' => 'Original Name',
                'city' => 'Original City',
                'state' => 'Original State'
            ]);

            $response = $this->putJson("/api/places/{$place->id}", [
                'city' => 'Updated City'
            ]);

            $response->assertStatus(200)
                    ->assertJsonPath('data.name', 'Original Name')
                    ->assertJsonPath('data.city', 'Updated City')
                    ->assertJsonPath('data.state', 'Original State');
        });

        it('validates unique name on update', function () {
            $place1 = Place::factory()->create(['name' => 'Place One']);
            $place2 = Place::factory()->create(['name' => 'Place Two']);

            $response = $this->putJson("/api/places/{$place2->id}", [
                'name' => 'Place One'
            ]);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['name']);
        });

        it('allows updating place with same name', function () {
            $place = Place::factory()->create(['name' => 'Same Name']);

            $response = $this->putJson("/api/places/{$place->id}", [
                'name' => 'Same Name',
                'city' => 'New City'
            ]);

            $response->assertStatus(200)
                    ->assertJsonPath('data.name', 'Same Name')
                    ->assertJsonPath('data.city', 'New City');
        });
    });

    describe('DELETE /api/places/{id}', function () {
        it('deletes a place', function () {
            $place = Place::factory()->create();

            $response = $this->deleteJson("/api/places/{$place->id}");

            $response->assertStatus(200)
                    ->assertJsonPath('message', 'Place deleted successfully');

            $this->assertDatabaseMissing('places', ['id' => $place->id]);
        });

        it('returns 404 for non-existent place', function () {
            $response = $this->deleteJson('/api/places/999');

            $response->assertStatus(404);
        });
    });
});