<?php

namespace Database\Factories;

use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true) . ' ' . $this->faker->randomElement(['Park', 'Beach', 'Museum', 'Plaza', 'Center', 'Bridge', 'Tower', 'Memorial']);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'city' => $this->faker->cityPrefix . ' ' . $this->faker->lastName,
            'state' => $this->faker->stateAbbr,
        ];
    }
}