<?php

namespace App\Filament\Resources\Households\Pages;

use App\Filament\Resources\Households\HouseholdResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\District;
use App\Models\Household;



class CreateHousehold extends CreateRecord
{
    protected static string $resource = HouseholdResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $district = District::find($data['district_id']);

        $data['household_code'] = Household::generateCode($district);
        $data['registered_by']  = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
