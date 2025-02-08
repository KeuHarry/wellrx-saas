<?php

namespace Database\Seeders;

use App\Models\Tenant\Shelf;
use Illuminate\Database\Seeder;

class ShelfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shelvesName = [
            'Shelf A',
            'Shelf B',
            'Shelf C',
            'Shelf D',
            'Shelf E',
            'Shelf F',
            'Shelf G',
            'Shelf H',
            'Shelf I',
            'Shelf J',
        ];

        $shelves = array_map(function ($name) {
            return [
                'name' => $name,
                'description' => $name.' description',
            ];
        }, $shelvesName);

        foreach ($shelves as $shelf) {
            Shelf::factory()->create($shelf);
        }

    }
}
