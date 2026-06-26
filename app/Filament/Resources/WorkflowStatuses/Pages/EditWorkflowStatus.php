<?php

namespace App\Filament\Resources\WorkflowStatuses\Pages;

use App\Filament\Resources\WorkflowStatuses\WorkflowStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflowStatus extends EditRecord
{
    protected static string $resource = WorkflowStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
