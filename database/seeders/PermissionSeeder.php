<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = [
            'survey_submission',
            'household',
            'enumerator_deployment',
            'qa_assignment',
            'qa_review',
            'survey_campaign',
            'region',
            'district',
            'community',
            'workflow_status',
            'report_template',
            'audit_log',
            'user',
        ];

        $actions = ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}_{$resource}"]);
            }
        }

        // Assign permissions to roles
        $supervisor = Role::findByName('supervisor');
        $supervisor->syncPermissions(Permission::where('name', 'like', '%survey_submission%')
            ->orWhere('name', 'like', '%household%')
            ->orWhere('name', 'like', '%enumerator_deployment%')
            ->orWhere('name', 'like', '%qa_assignment%')
            ->orWhere('name', 'like', '%qa_review%')
            ->pluck('name')
        );

        $enumerator = Role::findByName('enumerator');
        $enumerator->syncPermissions(Permission::where('name', 'like', '%survey_submission%')
            ->orWhere('name', 'like', '%household%')
            ->pluck('name')
        );

        $qaOfficer = Role::findByName('qa_officer');
        $qaOfficer->syncPermissions(Permission::where('name', 'like', '%survey_submission%')
            ->orWhere('name', 'like', '%qa_review%')
            ->orWhere('name', 'like', '%qa_assignment%')
            ->pluck('name')
        );
    }
}