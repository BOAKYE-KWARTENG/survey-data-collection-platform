<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class GeographicSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Greater Accra', 'code' => 'GAR'],
            ['name' => 'Ashanti', 'code' => 'ASH'],
            ['name' => 'Northern', 'code' => 'NOR'],
            ['name' => 'Western', 'code' => 'WES'],
            ['name' => 'Eastern', 'code' => 'EAS'],
        ];

        foreach ($regions as $regionData) {
            Region::firstOrCreate(
                ['code' => $regionData['code']],
                ['name' => $regionData['name']]
            );
        }

        $districts = [
            ['region' => 'GAR', 'name' => 'Accra Metropolitan', 'code' => 'AMA'],
            ['region' => 'GAR', 'name' => 'Tema Metropolitan', 'code' => 'TMA'],
            ['region' => 'GAR', 'name' => 'Ga East Municipal', 'code' => 'GEM'],
            ['region' => 'ASH', 'name' => 'Kumasi Metropolitan', 'code' => 'KMA'],
            ['region' => 'ASH', 'name' => 'Oforikrom Municipal', 'code' => 'OFM'],
            ['region' => 'NOR', 'name' => 'Tamale Metropolitan', 'code' => 'TAM'],
            ['region' => 'WES', 'name' => 'Sekondi-Takoradi Metropolitan', 'code' => 'STM'],
            ['region' => 'EAS', 'name' => 'New Juaben Municipal', 'code' => 'NJM'],
        ];

        foreach ($districts as $districtData) {
            $region = Region::where('code', $districtData['region'])->first();

            District::firstOrCreate(
                ['code' => $districtData['code']],
                [
                    'region_id' => $region->id,
                    'name' => $districtData['name'],
                ]
            );
        }
    }
}
