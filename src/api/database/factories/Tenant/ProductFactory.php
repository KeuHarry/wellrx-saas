<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Category;
use App\Models\Tenant\Fee;
use App\Models\Tenant\Shelf;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_code' => $this->faker->unique()->ean13, // Unique product code
            'stock_number' => $this->faker->randomFloat(2, 10, 100), // Stock number
            'label' => $this->faker->word, // Random word
            'is_prescription' => $this->faker->boolean, // 0 or 1
            'is_generic' => $this->faker->boolean, // 0 or 1
            'generic_product_id' => null, // Placeholder for generic product FK
            'barcode' => $this->faker->unique()->ean13, // Barcode
            'cip_code' => $this->faker->optional()->bothify('CIP###-####'), // Optional CIP code
            'ndc' => $this->faker->optional()->bothify('NDC###-####'), // Optional NDC code
            'acq_cost' => $this->faker->randomFloat(2, 1, 100), // Acquisition cost
            'wholesale_cost' => $this->faker->randomFloat(2, 1, 200), // Wholesale cost
            'retail_cost' => $this->faker->randomFloat(2, 1, 300), // Retail cost
            'unit_cost' => $this->faker->randomFloat(2, 1, 50), // Unit cost
            'description' => $this->faker->sentence, // Product description
            'qoh' => $this->faker->numberBetween(0, 500), // Quantity on hand
            'min_quantity' => $this->faker->optional()->numberBetween(10, 50), // Min quantity
            'max_quantity' => $this->faker->optional()->numberBetween(100, 500), // Max quantity
            'is_decomposable' => $this->faker->boolean, // 0 or 1
            'is_active' => $this->faker->boolean, // 0 or 1
            'pkg_size' => $this->faker->numberBetween(1, 10), // Package size
            'shelf_id' => Shelf::inRandomOrder()->first()->id, // Random shelf
            'product_category_id' => Category::inRandomOrder()->first()->id, // Random category
            'fee_id' => Fee::inRandomOrder()->first()->id ?? null, // Optional FK
        ];
    }
}
