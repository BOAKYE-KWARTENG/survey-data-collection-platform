<?php

namespace App\Filament\Resources\WorkflowStatuses;

use App\Filament\Resources\WorkflowStatuses\Pages\CreateWorkflowStatus;
use App\Filament\Resources\WorkflowStatuses\Pages\EditWorkflowStatus;
use App\Filament\Resources\WorkflowStatuses\Pages\ListWorkflowStatuses;
use App\Filament\Resources\WorkflowStatuses\Schemas\WorkflowStatusForm;
use App\Filament\Resources\WorkflowStatuses\Tables\WorkflowStatusesTable;
use App\Models\WorkflowStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkflowStatusResource extends Resource
{
    protected static ?string $model = WorkflowStatus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $recordTitleAttribute = 'WorkflowStatuses';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return 'Workflow Configuration';
    }


    public static function form(Schema $schema): Schema
    {
        return WorkflowStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflowStatuses::route('/'),
            'create' => CreateWorkflowStatus::route('/create'),
            'edit' => EditWorkflowStatus::route('/{record}/edit'),
        ];
    }
}
