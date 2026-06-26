<?php

namespace App\Filament\Resources\WorkflowStatuses\Pages;

use App\Filament\Resources\WorkflowStatuses\WorkflowStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflowStatuses extends ListRecords
{
    protected static string $resource = WorkflowStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
