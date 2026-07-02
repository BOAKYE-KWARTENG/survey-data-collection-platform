<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch all districts from the database
        $districts = DB::table('districts')->get(['id', 'name', 'code']);

        if ($districts->isEmpty()) {
            $this->command->error('No districts found in the database. Please run your GeographicSeeder first.');
            return;
        }

        $this->command->info("Seeding 10 communities for each of the {$districts->count()} districts...");

        $communitiesToInsert = [];
        $totalInserted = 0;

        foreach ($districts as $district) {
            // Loop 10 times to generate exactly 10 communities per district
            for ($i = 1; $i <= 10; $i++) {
                
                // Example: "Accra Metropolitan Community 1"
                $communityName = "{$district->name} Community {$i}";
                
                // Example: "777-AMA-COM01" (padded with zero for cleaner code lengths)
                $suffix = str_pad($i, 2, '0', STR_PAD_LEFT);
                $communityCode = "{$district->code}-COM{$suffix}";

                $communitiesToInsert[] = [
                    'district_id' => $district->id,
                    'name'        => $communityName,
                    'code'        => $communityCode,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            // Chunk and insert periodically to prevent memory exhaustion (keeps arrays lean)
            if (count($communitiesToInsert) >= 500) {
                DB::table('communities')->insertOrIgnore($communitiesToInsert);
                $totalInserted += count($communitiesToInsert);
                $communitiesToInsert = []; // Reset chunk buffer
            }
        }

        // Insert any remaining communities left in the buffer
        if (!empty($communitiesToInsert)) {
            DB::table('communities')->insertOrIgnore($communitiesToInsert);
            $totalInserted += count($communitiesToInsert);
        }

        $this->command->info("Successfully processed {$totalInserted} communities dynamically!");
    }
}