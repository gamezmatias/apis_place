<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $places = [
            [
                'name' => 'Central Park',
                'slug' => 'central-park',
                'city' => 'New York',
                'state' => 'New York'
            ],
            [
                'name' => 'Golden Gate Bridge',
                'slug' => 'golden-gate-bridge',
                'city' => 'San Francisco',
                'state' => 'California'
            ],
            [
                'name' => 'Times Square',
                'slug' => 'times-square',
                'city' => 'New York',
                'state' => 'New York'
            ],
            [
                'name' => 'Hollywood Sign',
                'slug' => 'hollywood-sign',
                'city' => 'Los Angeles',
                'state' => 'California'
            ],
            [
                'name' => 'Miami Beach',
                'slug' => 'miami-beach',
                'city' => 'Miami',
                'state' => 'Florida'
            ],
            [
                'name' => 'Lincoln Memorial',
                'slug' => 'lincoln-memorial',
                'city' => 'Washington',
                'state' => 'District of Columbia'
            ],
            [
                'name' => 'Space Needle',
                'slug' => 'space-needle',
                'city' => 'Seattle',
                'state' => 'Washington'
            ],
            [
                'name' => 'Navy Pier',
                'slug' => 'navy-pier',
                'city' => 'Chicago',
                'state' => 'Illinois'
            ]
        ];

        foreach ($places as $place) {
            Place::create($place);
        }

        // Create additional random places
        Place::factory()->count(15)->create();
    }
}