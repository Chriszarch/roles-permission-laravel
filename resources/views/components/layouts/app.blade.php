<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>

    {{-- Preconexión a Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&loading=async&callback=initGoogleMaps"
        async defer></script>

    @if (Route::currentRouteName() == 'google-places')
        <script>
            function initGoogleMaps() {
                // Este callback se ejecuta cuando Google Maps JS está listo.
                // No hacemos nada aquí si inicializamos desde Alpine con x-init.
                // Pero la existencia de esta función evita errores si usas callback param.
            }
        </script>
    @endif


</head>

<body class="min-h-screen lg:max-w-7xl mx-auto font-sans antialiased text-white" style="background-image: url('{{ asset('storage/bg-app.webp') }}')">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible>

            {{-- BRAND --}}
            <div class="flex items-center justify-center -mb-8">
                <img src="{{ asset('storage/horizontal-icon-mr-insight.webp') }}" alt="Logo"
                    class="px-5 pt-4 h-14 md:h-32 object-contain">
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if ($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff"
                                no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-list-item>

                    {{-- Role Selector --}}
                    <livewire:role-selector />

                    <x-menu-separator />
                @endif

                <x-menu-item title="Dashboard" icon="o-chart-pie" link="/" />

                {{-- MENÚ CON CONTROL DE PERMISOS --}}
                @can('users.view')
                    <x-menu-item title="Users" icon="o-users" link="/users" />
                @endcan

                @can('qr.view')
                    <x-menu-item title="QR Codes" icon="o-qr-code" link="/qr-codes" />
                @endcan

                <x-menu-item title="Links de Reseñas" icon="o-map-pin" link="/google-places" />

                {{-- Solo admin puede ver configuraciones --}}
                @if (auth()->user()?->hasRole('admin'))
                    <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                        <x-menu-item title="Roles" icon="o-user-circle" link="/roles" />
                        <x-menu-item title="Permissions" icon="o-key" link="/permissions" />
                    </x-menu-sub>
                @endif
            </x-menu>
        </x-slot:sidebar>



        {{-- The `$slot` goes here --}}
        <x-slot:content>
            <!-- HEADER -->
            <x-header title="{{ $title ?? 'Dashboard' }}" separator />

            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />
</body>

</html>
