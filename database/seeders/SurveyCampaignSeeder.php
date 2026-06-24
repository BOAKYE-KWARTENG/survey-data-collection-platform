<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SurveyCampaign;

class SurveyCampaignSeeder extends Seeder
{
    public function run(): void
    {
        SurveyCampaign::firstOrCreate(
            ['name' => 'GNHR Household Survey 2026'],
            [
                'description' => 'Ghana National Household Registry Survey for 2026.',
                'start_date'  => '2026-01-01',
                'end_date'    => '2026-12-31',
                'status'      => 'active',
            ]
        );
    }
}
