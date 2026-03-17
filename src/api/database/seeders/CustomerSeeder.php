<?php

namespace Database\Seeders;

use App\Models\Tenant\Customer;
use Faker\Factory;
use Illuminate\Database\Seeder;

/**
 * Fills the customers table with fictional customers.
 */
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 15; $i++) {
            $name = $faker->unique()->name();
            Customer::firstOrCreate(
                ['name' => $name],
                [
                    'email' => $faker->unique()->safeEmail(),
                    'credit_limit' => $faker->randomFloat(2, 0, 3000),
                    'purchased_total' => 0,
                    'balance' => 0,
                    'deposit' => 0,
                    'phone' => $faker->phoneNumber(),
                    'address' => $faker->optional()->streetAddress(),
                    'city' => $faker->optional()->city(),
                ]
            );
        }
    }
}
