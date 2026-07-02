<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;
use Illuminate\Support\Facades\File;

class GeographicSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Regions
        $this->seedRegions();

        // Seed Districts from CSV
        $this->seedDistricts();
    }

    private function seedRegions(): void
    {
        $regions = [
            ['name' => 'Greater Accra',  'code' => '777'],
            ['name' => 'Ashanti',        'code' => '222'],
            ['name' => 'Western',        'code' => '151515'],
            ['name' => 'Central',        'code' => '555'],
            ['name' => 'Eastern',        'code' => '666'],
            ['name' => 'Volta',          'code' => '141414'],
            ['name' => 'Oti',            'code' => '101010'],
            ['name' => 'Bono',           'code' => '444'],
            ['name' => 'Bono East',      'code' => '333'],
            ['name' => 'Ahafo',          'code' => '111'],
            ['name' => 'Northern',       'code' => '999'],
            ['name' => 'Savannah',       'code' => '111111'],
            ['name' => 'North East',     'code' => '888'],
            ['name' => 'Upper East',     'code' => '121212'],
            ['name' => 'Upper West',     'code' => '131313'],
            ['name' => 'Western North',  'code' => '161616'],
        ];

        foreach ($regions as $regionData) {
            Region::firstOrCreate(
                ['code' => $regionData['code']],
                ['name' => $regionData['name']]
            );
        }

        $this->command->info('Regions seeded successfully.');
    }

    private function seedDistricts(): void
    {
        $csvPath = database_path('data/ghana_districts.csv');

        if (!File::exists($csvPath)) {
            $this->command->error('CSV file not found at: ' . $csvPath);
            return;
        }

        $handle = fopen($csvPath, 'r');

        if (!$handle) {
            $this->command->error('Could not open CSV file.');
            return;
        }

        // Skip header row
        $header = fgetcsv($handle);

        $inserted  = 0;
        $skipped   = 0;
        $errors    = 0;

        while (($row = fgetcsv($handle)) !== false) {
            [$regionCode, $districtName, $districtCode, $capital] = $row;

            $region = Region::where('code', trim($regionCode))->first();

            if (!$region) {
                $this->command->warn("Region not found for code: {$regionCode}. Skipping {$districtName}.");
                $errors++;
                continue;
            }

            $existing = District::where('code', trim($districtCode))->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            District::create([
                'region_id' => $region->id,
                'name'      => trim($districtName),
                'code'      => trim($districtCode),
            ]);

            $inserted++;
        }

        fclose($handle);

        $this->command->info("Districts seeded: {$inserted} inserted, {$skipped} skipped, {$errors} errors.");
    }
}