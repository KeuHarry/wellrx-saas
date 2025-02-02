<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Product;
use App\Models\Tenant\Supplier;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 50);
        $acqCost = $this->faker->randomFloat(2, 1, 100);
        $wholesaleCost = $acqCost + $this->faker->randomFloat(2, 1, 10);
        $retailCost = $wholesaleCost + $this->faker->randomFloat(2, 1, 10);
        $unitCost = $retailCost;

        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'quantity' => $quantity,
            'before_qoh' => 0,
            'after_qoh' => $quantity,
            'type' => 1,
            'expiry_date' => $this->faker->dateTimeBetween('+6 month', '+1 years')->format('Y-m-d'),
            'acq_cost' => $acqCost,
            'wholesale_cost' => $wholesaleCost,
            'retail_cost' => $retailCost,
            'unit_cost' => $unitCost,
            'sub_total' => $quantity * $acqCost,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'reason' => $this->faker->optional()->sentence,
            'created_by' => User::inRandomOrder()->first()->id,
            'order_item_id' => null,
        ];
    }
}
