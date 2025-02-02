<?php

namespace Database\Seeders;

use App\Models\Tenant\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch 10 random products
        $randomProducts = DB::table('products')->inRandomOrder()->limit(10)->get();

        $inventories = [];

        foreach ($randomProducts as $product) {
            $quantity = $faker->numberBetween(1, 50);

            $inventories[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'before_qoh' => 0,
                'after_qoh' => $quantity,
                'type' => 1,
                'expiry_date' => $faker->dateTimeBetween('+6 month', '+1 years')->format('Y-m-d'),
                'acq_cost' => $product->acq_cost,
                'wholesale_cost' => $product->wholesale_cost,
                'retail_cost' => $product->retail_cost,
                'unit_cost' => $product->unit_cost,
                'sub_total' => $quantity * $product->acq_cost,
                'supplier_id' => 1,
                'reason' => $faker->optional()->sentence,
                'created_by' => 1,
                'order_item_id' => null, // Optional, can be filled with real order_item IDs
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($inventories as $inventory) {
            Inventory::factory()->create($inventory);
        }
    }
}
