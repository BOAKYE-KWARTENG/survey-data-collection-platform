<x-filament-panels::page>

    {{-- Role Selector --}}
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    @if ($this->selected_role)

        {{-- Quick Actions --}}
        <div class="flex gap-3">
            <x-filament::button
                wire:click="selectAll"
                color="gray"
                size="sm"
                icon="heroicon-o-check-circle">
                Select All
            </x-filament::button>
            <x-filament::button
                wire:click="clearAll"
                color="danger"
                size="sm"
                icon="heroicon-o-x-circle">
                Clear All
            </x-filament::button>
        </div>

        {{-- Permissions Grid --}}
        @foreach ($this->getGroupedPermissions() as $group => $permissions)
            <x-filament::section :heading="str_replace('_', ' ', ucwords($group, '_'))">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg border
                            {{ in_array($permission, $this->selected_permissions)
                                ? 'bg-primary-50 border-primary-300 dark:bg-primary-900/20 dark:border-primary-700'
                                : 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-700' }}">
                            <input
                                type="checkbox"
                                wire:click="togglePermission('{{ $permission }}')"
                                {{ in_array($permission, $this->selected_permissions) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                            />
                            <span class="text-xs text-gray-700 dark:text-gray-300">
                                {{ str_replace('_', ' ', $permission) }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </x-filament::section>
        @endforeach

        {{-- Save Button at Bottom --}}
        <div class="flex justify-end">
            <x-filament::button
                wire:click="savePermissions"
                color="success"
                icon="heroicon-o-check">
                Save Permissions
            </x-filament::button>
        </div>

    @else
        <x-filament::section>
            <p class="text-gray-400 text-sm text-center py-4">
                Select a role above to manage its permissions.
            </p>
        </x-filament::section>
    @endif

</x-filament-panels::page>