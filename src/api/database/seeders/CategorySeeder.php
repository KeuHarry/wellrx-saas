<?php

namespace Database\Seeders;

use App\Models\Tenant\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesName = [
            'Pain Relievers',
            'Vitamins & Supplements',
            'Cough & Cold Remedies',
            'Antibiotics',
            'Antifungals',
            'Skin Care Products',
            'Allergy Medications',
            'Digestive Aids',
            'Diabetes Supplies',
            'First Aid Supplies',
        ];

        $categories = array_map(function ($name) {
            return [
                'name' => $name,
                'markup' => round(mt_rand(100, 150) / 10, 2)
            ];
        }, $categoriesName);

        foreach ($categories as $category) {
            Category::factory()->create($category);
        }
    }
}
