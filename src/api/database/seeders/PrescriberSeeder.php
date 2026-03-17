<?php

namespace Database\Seeders;

use App\Models\Tenant\Prescriber;
use Faker\Factory;
use Illuminate\Database\Seeder;

/**
 * Fills the prescribers table with fictional prescribing physicians.
 */
class PrescriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $doctors = [
            ['first_name' => 'Vera', 'last_name' => 'Asante'],
            ['first_name' => 'Marie', 'last_name' => 'Dubois'],
            ['first_name' => 'Afua', 'last_name' => 'Affori'],
            ['first_name' => 'Sophie', 'last_name' => 'Appiah'],
            ['first_name' => 'Thomas', 'last_name' => 'Simon'],
            ['first_name' => 'Isabelle', 'last_name' => 'Asuma'],
            ['first_name' => 'Georges', 'last_name' => 'Mensah'],
            ['first_name' => 'Akosua', 'last_name' => 'Addai'],
            ['first_name' => 'Yaw', 'last_name' => 'Boateng'],
            ['first_name' => 'Victor', 'last_name' => 'Nkrumah'],
        ];

        foreach ($doctors as $doc) {
            Prescriber::firstOrCreate(
                [
                    'first_name' => $doc['first_name'],
                    'last_name' => $doc['last_name'],
                ],
                [
                    'registration_number' => $faker->unique()->numerify('MED########'),
                    'phone' => $faker->phoneNumber(),
                    'address' => $faker->optional()->streetAddress(),
                ]
            );
        }
    }
}
