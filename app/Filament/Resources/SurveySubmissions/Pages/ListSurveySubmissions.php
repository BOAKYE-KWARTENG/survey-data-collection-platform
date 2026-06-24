<?php

namespace App\Filament\Resources\SurveySubmissions\Pages;

use App\Filament\Resources\SurveySubmissions\SurveySubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveySubmissions extends ListRecords
{
    protected static string $resource = SurveySubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
