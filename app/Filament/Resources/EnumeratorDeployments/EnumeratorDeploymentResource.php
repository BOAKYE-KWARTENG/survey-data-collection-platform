<?php

namespace App\Filament\Resources\EnumeratorDeployments;

use App\Filament\Resources\EnumeratorDeployments\Pages\CreateEnumeratorDeployment;
use App\Filament\Resources\EnumeratorDeployments\Pages\EditEnumeratorDeployment;
use App\Filament\Resources\EnumeratorDeployments\Pages\ListEnumeratorDeployments;
use App\Filament\Resources\EnumeratorDeployments\Schemas\EnumeratorDeploymentForm;
use App\Filament\Resources\EnumeratorDeployments\Tables\EnumeratorDeploymentsTable;
use App\Models\EnumeratorDeployment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EnumeratorDeploymentResource extends Resource
{
    protected static ?string $model = EnumeratorDeployment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    // protected static ?string $recordTitleAttribute = 'EnumeratorDeployment';


    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return 'Field Operations';
    }


    public static function form(Schema $schema): Schema
    {
        return EnumeratorDeploymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnumeratorDeploymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEnumeratorDeployments::route('/'),
            'create' => CreateEnumeratorDeployment::route('/create'),
            'edit'   => EditEnumeratorDeployment::route('/{record}/edit'),
        ];
    }
}