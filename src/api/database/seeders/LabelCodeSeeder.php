<?php

namespace Database\Seeders;

use App\Models\Tenant\LabelCode;
use Illuminate\Database\Seeder;

/**
 * Fills the label_codes table with common pharmaceutical labeling codes.
 */
class LabelCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = [
            ['label_code' => 'RX', 'label' => 'Prescription required'],
            ['label_code' => 'OTC', 'label' => 'Over the counter'],
            ['label_code' => 'GSL', 'label' => 'General sale list'],
            ['label_code' => 'POM', 'label' => 'Prescription only medicine'],
            ['label_code' => 'PO', 'label' => 'Pharmacy only'],
            ['label_code' => 'CD', 'label' => 'Controlled drug'],
            ['label_code' => 'CD2', 'label' => 'Schedule 2 controlled'],
            ['label_code' => 'CD3', 'label' => 'Schedule 3 controlled'],
            ['label_code' => 'CD4', 'label' => 'Schedule 4 controlled'],
            ['label_code' => 'CD5', 'label' => 'Schedule 5 controlled'],
            ['label_code' => 'REF', 'label' => 'Refrigerate'],
            ['label_code' => 'FZ', 'label' => 'Store in freezer'],
            ['label_code' => 'LTD', 'label' => 'Limited quantity'],
            ['label_code' => 'DS', 'label' => 'Dose as directed'],
            ['label_code' => 'EXT', 'label' => 'External use only'],
        ];

        foreach ($labels as $item) {
            LabelCode::firstOrCreate(
                ['label_code' => $item['label_code']],
                ['label' => $item['label']]
            );
        }
    }
}
