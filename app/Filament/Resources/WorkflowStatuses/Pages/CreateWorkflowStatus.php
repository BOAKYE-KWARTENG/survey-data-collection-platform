<?php

namespace App\Filament\Resources\WorkflowStatuses\Pages;

use App\Filament\Resources\WorkflowStatuses\WorkflowStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkflowStatus extends CreateRecord
{
    protected static string $resource = WorkflowStatusResource::class;
}
