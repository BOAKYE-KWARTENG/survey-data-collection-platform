<?php

namespace App\Filament\Resources\EnumeratorDeployments\Pages;

use App\Filament\Resources\EnumeratorDeployments\EnumeratorDeploymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEnumeratorDeployment extends EditRecord
{
    protected static string $resource = EnumeratorDeploymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
