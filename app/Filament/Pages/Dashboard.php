<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Match parent signature: BackedEnum|string|null
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    public function getColumns(): int|array
    {
        return 2;
    }
}