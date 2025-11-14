<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-neutral rounded-3xl shadow-xl p-8 space-y-8 relative overflow-hidden">
            <!-- Decorative elements -->
            <div
                class="absolute inset-0 bg-grid-slate-100 opacity-[0.04] [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]">
            </div>
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary-100 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-indigo-100 rounded-full blur-3xl opacity-30"></div>

            <!-- Logo/Brand -->
            <div class="text-center space-y-2 relative">
                <div class="inline-flex items-center justify-center w-24 h-24">
                    <img src="{{ asset('storage/vertical-icon-mr-insight.webp') }}" alt="Logo" class="w-20 h-20">
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-primary">Bienvenido de nuevo</h2>
                <p class="text-black font-semibold font-title text-lg">Inicia sesión para acceder a tu cuenta</p>
            </div>

            <!-- Login Form -->
            <form wire:submit.prevent="login" class="space-y-6 relative">
                <!-- Email Input -->
                <x-input label="Correo electrónico" wire:model="email" icon="o-envelope" type="email"
                    placeholder="usuario@ejemplo.com" class="rounded-xl shadow-sm" />

                <!-- Password Input -->
                <x-input label="Contraseña" wire:model="password" icon="o-lock-closed" type="password"
                    placeholder="••••••••" class="rounded-xl shadow-sm" />

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <x-checkbox label="Recordarme" wire:model="remember" class="text-primary-600" />
                </div>

                <!-- Submit Button -->
                <x-button type="submit" class="w-full rounded-full bg-secondary font-semibold text-white shadow-lg shadow-secondary-500/20">
                    Iniciar sesión
                </x-button>
            </form>
        </div>
    </div>
</div>
