<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkflowStatus;

class WorkflowStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Draft',       'color' => 'gray',    'sort_order' => 1, 'is_default' => true,  'is_final' => false],
            ['name' => 'Submitted',   'color' => 'info',    'sort_order' => 2, 'is_default' => false, 'is_final' => false],
            ['name' => 'QA Assigned', 'color' => 'warning', 'sort_order' => 3, 'is_default' => false, 'is_final' => false],
            ['name' => 'QA Review',   'color' => 'primary', 'sort_order' => 4, 'is_default' => false, 'is_final' => false],
            ['name' => 'Rejected',    'color' => 'danger',  'sort_order' => 5, 'is_default' => false, 'is_final' => false],
            ['name' => 'Approved',    'color' => 'success', 'sort_order' => 6, 'is_default' => false, 'is_final' => true],
            ['name' => 'Published',   'color' => 'success', 'sort_order' => 7, 'is_default' => false, 'is_final' => true],
        ];

        foreach ($statuses as $status) {
            WorkflowStatus::firstOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}