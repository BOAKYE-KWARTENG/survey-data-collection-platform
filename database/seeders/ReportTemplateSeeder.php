<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReportTemplate;

class ReportTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'        => 'District Coverage Report',
                'description' => 'Shows household and submission counts per district.',
                'type'        => ReportTemplate::DISTRICT_COVERAGE,
                'is_active'   => true,
            ],
            [
                'name'        => 'Enumerator Performance Report',
                'description' => 'Shows submissions, approvals and rejections per enumerator.',
                'type'        => ReportTemplate::ENUMERATOR_PERFORMANCE,
                'is_active'   => true,
            ],
            [
                'name'        => 'QA Performance Report',
                'description' => 'Shows reviews, approval rates and rejection rates per QA officer.',
                'type'        => ReportTemplate::QA_PERFORMANCE,
                'is_active'   => true,
            ],
            [
                'name'        => 'Financial Inclusion Report',
                'description' => 'Shows bank account, mobile money, savings and insurance metrics.',
                'type'        => ReportTemplate::FINANCIAL_INCLUSION,
                'is_active'   => true,
            ],
            [
                'name'        => 'Gender Report',
                'description' => 'Shows gender breakdown across all survey responses.',
                'type'        => ReportTemplate::GENDER_REPORT,
                'is_active'   => true,
            ],
            [
                'name'        => 'Employment Report',
                'description' => 'Shows employment status, sector and income distribution.',
                'type'        => ReportTemplate::EMPLOYMENT_REPORT,
                'is_active'   => true,
            ],
        ];

        foreach ($templates as $template) {
            ReportTemplate::firstOrCreate(
                ['type' => $template['type']],
                $template
            );
        }
    }
}
