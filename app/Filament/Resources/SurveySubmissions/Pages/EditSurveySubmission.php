<?php

namespace App\Filament\Resources\SurveySubmissions\Pages;

use App\Filament\Resources\SurveySubmissions\SurveySubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveySubmission extends EditRecord
{
    protected static string $resource = SurveySubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
