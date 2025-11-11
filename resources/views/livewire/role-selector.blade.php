<div>
    @if (count($availableRoles) > 1)
        <x-menu-sub title="{{ $activeRole }}" icon="o-shield-check">
            @foreach ($availableRoles as $roleName)
                <x-menu-item title="{{ $roleName }}" icon="{{ $activeRole === $roleName ? 'o-check' : '' }}"
                    wire:click="updateActiveRole('{{ $roleName }}')" />
            @endforeach
        </x-menu-sub>
    @endif
</div>
