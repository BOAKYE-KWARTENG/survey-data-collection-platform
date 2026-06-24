<?php

namespace App\Filament\Resources\SurveyCampaigns\Pages;

use App\Filament\Resources\SurveyCampaigns\SurveyCampaignResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveyCampaigns extends ListRecords
{
    protected static string $resource = SurveyCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
