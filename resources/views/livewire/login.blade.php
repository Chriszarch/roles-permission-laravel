<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <div class="w-full max-w-md">
        <!-- Card Container -->
        <div class="bg-white rounded-3xl shadow-xl p-8 space-y-8 relative overflow-hidden">
            <!-- Decorative elements -->
            <div
                class="absolute inset-0 bg-grid-slate-100 opacity-[0.04] [mask-image:linear-gradient(0deg,white,rgba(255,255,255,0.6))]">
            </div>
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary-100 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-indigo-100 rounded-full blur-3xl opacity-30"></div>

            <!-- Logo/Brand -->
            <div class="text-center space-y-2 relative">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-primary-500 to-indigo-500 shadow-lg shadow-primary-500/20 mb-4 ring-4 ring-white">
                    <x-icon name="o-finger-print" class="w-8 h-8 text-white" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Bienvenido de nuevo</h2>
                <p class="text-gray-600 text-sm">Inicia sesión para acceder a tu cuenta</p>
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
                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <x-button type="submit" primary xl class="w-full bg-purple-200 font-semibold shadow-lg shadow-primary-500/20">
                    Iniciar sesión
                </x-button>

                <!-- Sign Up Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes una cuenta?
                        <a href="#" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
                            Regístrate ahora
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
