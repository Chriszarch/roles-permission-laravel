<div>
    @if (count($availableRoles) > 1)
        <x-dropdown>
            <x-slot:trigger>
                <x-button class="btn btn-ghost gap-2">
                    <x-icon name="o-shield-check" class="w-4 h-4" />
                    <span class="text-sm">{{ $activeRole }}</span>
                    <x-icon name="o-chevron-down" class="w-4 h-4" />
                </x-button>
            </x-slot:trigger>

            @foreach ($availableRoles as $roleName)
                <x-menu-item title="{{ $roleName }}" icon="{{ $activeRole === $roleName ? 'o-check' : '' }}"
                    wire:click="updateActiveRole('{{ $roleName }}')" />
            @endforeach
        </x-dropdown>
    @endif
</div>
