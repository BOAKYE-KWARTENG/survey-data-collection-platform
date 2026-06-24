<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\User;
use Illuminate\Support\Facades\Hash;



class EnumeratorUserSeeder extends Seeder
{
    public function run(): void
    {
        $enumerators = [
            ['name' => 'Kwame Mensah',   'email' => 'kwame@survey.com'],
            ['name' => 'Ama Asante',     'email' => 'ama@survey.com'],
            ['name' => 'Kofi Boateng',   'email' => 'kofi@survey.com'],
            ['name' => 'Akosua Darko',   'email' => 'akosua@survey.com'],
        ];

        foreach ($enumerators as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('enumerator');
        }

        // Also create a supervisor
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@survey.com'],
            [
                'name'     => 'District Supervisor',
                'password' => Hash::make('password'),
            ]
        );
        $supervisor->assignRole('supervisor');
    }
}
