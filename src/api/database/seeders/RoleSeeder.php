<?php

namespace Database\Seeders;

use App\Models\Tenant\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesName = [
            'Pharmacist',     // Responsible for dispensing medications
            'Technician',     // Pharmacy technician
            'Cashier',        // Handles payments and billing
            'Stock Manager',  // Manages inventory and stock levels
            'Customer Service', // Handles customer queries and complaints
            'Delivery',       // Manages home deliveries
            'Manager',        // Oversees pharmacy operations
            'Inventory Clerk', // Manages stock records and order processing
            'Accountant',     // Responsible for financial operations
        ];

        $roles = array_map(function ($name) {
            return [
                'label' => $name,
                'description' => $name.' description',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $rolesName);

        Role::insert($roles);
    }
}
