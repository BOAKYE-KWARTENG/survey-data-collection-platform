<?php

namespace App\Filament\Pages\System;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionManager extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public ?string $selected_role = null;
    public array $selected_permissions = [];

    public function getTitle(): string|Htmlable
    {
        return 'Role Permission Manager';
    }

    public function getView(): string
    {
        return 'filament.pages.system.role-permission-manager';
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('selected_role')
                ->label('Select Role')
                ->options(Role::all()->pluck('name', 'name'))
                ->live()
                ->afterStateUpdated(function ($state) {
                    if ($state) {
                        $role = Role::findByName($state);
                        $this->selected_permissions = $role
                            ->permissions
                            ->pluck('name')
                            ->toArray();
                    }
                })
                ->required(),
        ];
    }

    public function getGroupedPermissions(): Collection
    {
        return Permission::all()
            ->groupBy(function ($permission) {
                $parts = explode('_', $permission->name);
                array_shift($parts);
                return implode('_', $parts);
            })
            ->map(function ($permissions) {
                return $permissions->pluck('name')->toArray();
            });
    }

    public function savePermissions(): void
    {
        if (!$this->selected_role) {
            Notification::make()
                ->title('Please select a role first.')
                ->warning()
                ->send();
            return;
        }

        $role = Role::findByName($this->selected_role);
        $role->syncPermissions($this->selected_permissions);

        Notification::make()
            ->title('Permissions updated successfully.')
            ->success()
            ->send();
    }

    public function togglePermission(string $permission): void
    {
        if (in_array($permission, $this->selected_permissions)) {
            $this->selected_permissions = array_values(
                array_filter($this->selected_permissions, fn($p) => $p !== $permission)
            );
        } else {
            $this->selected_permissions[] = $permission;
        }
    }

    public function selectAll(): void
    {
        $this->selected_permissions = Permission::all()->pluck('name')->toArray();
    }

    public function clearAll(): void
    {
        $this->selected_permissions = [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('savePermissions')
                ->label('Save Permissions')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action('savePermissions'),
        ];
    }
}