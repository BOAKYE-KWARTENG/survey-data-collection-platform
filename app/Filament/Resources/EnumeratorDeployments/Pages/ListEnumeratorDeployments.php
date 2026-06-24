<?php

namespace App\Filament\Resources\EnumeratorDeployments\Pages;

use App\Filament\Resources\EnumeratorDeployments\EnumeratorDeploymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnumeratorDeployments extends ListRecords
{
    protected static string $resource = EnumeratorDeploymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
