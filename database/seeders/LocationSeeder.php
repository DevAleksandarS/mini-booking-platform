<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['city' => 'Gradiska', 'country' => 'Bosnia and Herzegovina'],
            ['city' => 'Banja Luka', 'country' => 'Bosnia and Herzegovina'],
            ['city' => 'Beograd', 'country' => 'Serbia'],
            ['city' => 'Zagreb', 'country' => 'Croatia'],
            ['city' => 'Sarajevo', 'country' => 'Bosnia and Herzegovina'],
        ];

        foreach ($locations as $location) {
            Location::factory()->create($location);
        }
    }
}
