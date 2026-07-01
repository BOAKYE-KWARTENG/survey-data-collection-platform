<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->dehydrateStateUsing(fn ($state) => filled($state)
                        ? bcrypt($state)
                        : null
                    )
                    ->dehydrated(fn ($state) => filled($state))
                    ->label(fn (string $operation) => $operation === 'create'
                        ? 'Password'
                        : 'New Password (leave blank to keep current)'
                    ),
                Select::make('roles')
                    ->label('Role')
                    ->options(Role::all()->pluck('name', 'name'))
                    ->multiple()
                    ->preload()
                    ->required(),
            ]);
    }
}