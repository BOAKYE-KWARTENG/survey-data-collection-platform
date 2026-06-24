<?php

namespace App\Filament\Resources\SurveyCampaigns\Pages;

use App\Filament\Resources\SurveyCampaigns\SurveyCampaignResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyCampaign extends EditRecord
{
    protected static string $resource = SurveyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
