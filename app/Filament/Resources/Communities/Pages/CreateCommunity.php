<?php

namespace App\Filament\Resources\Communities\Pages;

use App\Filament\Resources\Communities\CommunityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunity extends CreateRecord
{
    protected static string $resource = CommunityResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    
}
