<?php

namespace Database\Seeders;

use App\Models\Tenant\Product;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        // Real product labels
        $realProductLabels = [
            'Ibuprofen 200mg Tablets',
            'Paracetamol 500mg Tablets',
            'Aspirin 75mg Tablets',
            'Vitamin C 1000mg Tablets',
            'Amoxicillin 500mg Capsules',
            'Omeprazole 20mg Capsules',
            'Cetirizine Hydrochloride 10mg Tablets',
            'Loratadine 10mg Tablets',
            'Dextromethorphan Syrup',
            'Cough Suppressant Lozenges',
            'Antacid Chewable Tablets',
            'Clotrimazole Cream',
            'Hydrocortisone 1% Cream',
            'Metformin 500mg Tablets',
            'Gliclazide 80mg Tablets',
            'Insulin Glargine Pen',
            'Salbutamol Inhaler',
            'Fluticasone Nasal Spray',
            'Chlorphenamine 4mg Tablets',
            'Acetaminophen Liquid',
            'Calcium Carbonate Tablets',
            'Iron Sulfate Capsules',
            'Magnesium Citrate Liquid',
            'Antihistamine Eye Drops',
            'Antibacterial Hand Gel',
            'Zinc Oxide Ointment',
            'Sodium Chloride Nasal Spray',
            'Aloe Vera Gel',
            'Glucosamine Capsules',
            'Cod Liver Oil Capsules',
            'Fish Oil Omega-3 Softgels',
            'Vitamin D3 400IU Tablets',
            'Vitamin B Complex Capsules',
            'Folic Acid 400mcg Tablets',
            'Multivitamin Gummies',
            'Electrolyte Powder Sachets',
            'Loperamide 2mg Capsules',
            'Antiseptic Cream',
            'Antifungal Powder',
            'Ear Wax Removal Drops',
            'Nicotine Replacement Gum',
            'Nicotine Replacement Patches',
            'Electrolyte Rehydration Tablets',
            'Oral Pain Relief Gel',
            'Teething Gel for Babies',
            'Digestive Enzymes Capsules',
            'Probiotic Capsules',
            'Laxative Soft Gel Capsules',
            'Anti-Diarrheal Solution',
            'Motion Sickness Tablets'
        ];

        $products = [];
        foreach ($realProductLabels as $label) {
            $products[] = [
                'product_code' => $faker->unique()->ean13, // Unique product code
                'stock_number' => $faker->randomFloat(2, 10, 100), // Stock number
                'label' => $label, // Use real product labels
                'is_prescription' => 0, // 0 or 1
                'is_generic' => $faker->boolean, // 0 or 1
                'generic_product_id' => null, // Placeholder, can be adjusted if generics are linked
                'barcode' => $faker->unique()->ean13, // Barcode
                'cip_code' => $faker->optional()->bothify('CIP###-####'), // Optional CIP code
                'ndc' => $faker->optional()->bothify('NDC###-####'), // Optional NDC code
                'acq_cost' => $faker->randomFloat(2, 10, 100), // Acquisition cost
                'wholesale_cost' => $faker->randomFloat(2, 70, 200), // Wholesale cost
                'retail_cost' => $faker->randomFloat(2, 90, 300), // Retail cost
                'unit_cost' => $faker->randomFloat(2, 90, 300), // Unit cost
                'description' => $faker->sentence, // Product description
                'qoh' => 0, // Quantity on hand
                'min_quantity' => $faker->optional()->numberBetween(1, 5), // Min quantity
                'max_quantity' => $faker->optional()->numberBetween(20, 100), // Max quantity
                'is_decomposable' => 0, // 0 or 1
                'is_active' => $faker->boolean, // 0 or 1
                'pkg_size' => 1, // Package size
                'shelf_id' => 1, // Foreign key (shelf)
                'product_category_id' => 1, // Foreign key (category)
                'created_at' => now(), // Timestamp
                'updated_at' => now(), // Timestamp
            ];
        }

        // Insert products into the database
        Product::insert($products);
    }
}
