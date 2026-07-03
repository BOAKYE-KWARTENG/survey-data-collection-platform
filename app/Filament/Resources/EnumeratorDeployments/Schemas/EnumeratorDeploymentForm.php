<?php

namespace App\Filament\Resources\EnumeratorDeployments\Schemas;

use App\Models\District;
use App\Models\SurveyCampaign;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class EnumeratorDeploymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->label('Campaign')
                    ->options(SurveyCampaign::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('district_id')
                    ->label('District')
                    ->options(District::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('enumerator_id')
                    ->label('Enumerator')
                    ->options(
                        User::role('enumerator')->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email')
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $user = \App\Models\User::create([
                            'name'     => $data['name'],
                            'email'    => $data['email'],
                            'password' => $data['password'],
                        ]);

                        $user->assignRole('enumerator');

                        return $user->id;
                    })
                    ->createOptionModalHeading('Create New Enumerator'),
                Select::make('status')
                    ->options([
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}