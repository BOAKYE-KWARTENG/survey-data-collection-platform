<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            GeographicSeeder::class,
            SurveyCampaignSeeder::class,
            EnumeratorUserSeeder::class,
            WorkflowStatusSeeder::class,
            ReportTemplateSeeder::class,
        ]);
    }
}
