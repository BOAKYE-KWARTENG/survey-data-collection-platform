<?php

namespace App\Filament\Resources\EnumeratorDeployments\Pages;

use App\Filament\Resources\EnumeratorDeployments\EnumeratorDeploymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEnumeratorDeployment extends CreateRecord
{
    protected static string $resource = EnumeratorDeploymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['assigned_by'] = auth()->id();
        $data['assigned_at'] = now();

        return $data;
    }
}
