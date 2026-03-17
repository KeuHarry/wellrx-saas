<?php

namespace Database\Seeders;

use App\Models\Tenant\Insurer;
use Faker\Factory;
use Illuminate\Database\Seeder;

/**
 * Fills the insurers table with fictional health insurers.
 */
class InsurerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $names = [
            'HealthFirst Mutual',
            'CarePlus Insurance',
            'MediGuard Assurance',
            'WellCare Health',
            'PrimeHealth Partners',
            'United PharmaCare',
            'Blue Cross Pharma',
            'Green Shield Plus',
            'SunLife Health',
            'AssurMed Solutions',
        ];

        foreach ($names as $name) {
            Insurer::firstOrCreate(
                ['name' => $name],
                [
                    'tax_payer_number' => $faker->unique()->numerify('TIN########'),
                    'registration_number' => $faker->unique()->numerify('REG#######'),
                    'credit_limit' => $faker->randomFloat(2, 5000, 50000),
                    'balance' => $faker->randomFloat(2, 0, 5000),
                    'deposit' => $faker->randomFloat(2, 0, 2000),
                    'address' => $faker->streetAddress(),
                    'neighborhood' => $faker->optional()->citySuffix(),
                ]
            );
        }
    }
}
