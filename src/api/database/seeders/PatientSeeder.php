<?php

namespace Database\Seeders;

use App\Models\Tenant\Insurer;
use App\Models\Tenant\Patient;
use Faker\Factory;
use Illuminate\Database\Seeder;

/**
 * Fills the patients table with fictional patients (optionally linked to an insurer).
 */
class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $insurerIds = Insurer::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            Patient::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'dob' => $faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
                'address' => $faker->optional()->streetAddress(),
                'city' => $faker->city(),
                'email' => $faker->optional()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'company' => $faker->optional()->company(),
                'insurer_id' => $insurerIds ? $faker->optional(0.6)->randomElement($insurerIds) : null,
                'caretaker_id' => null,
                'intermediary_id' => null,
                'allergies' => $faker->optional(0.3)->sentence(),
                'medecine_history' => $faker->optional(0.4)->sentence(),
            ]);
        }
    }
}
