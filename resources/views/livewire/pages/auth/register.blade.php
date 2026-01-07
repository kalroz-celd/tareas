<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">

        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white/70 backdrop-blur p-6 sm:p-8 shadow-lg">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(99,102,241,.12),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(14,165,233,.10),transparent_40%)]"></div>

            <div class="relative z-10">
                <!-- Header -->
                <div class="text-center">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Crear cuenta
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Registro de administrador del sistema.
                    </p>
                </div>

                <form wire:submit="register" class="mt-8 space-y-5">
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input
                            wire:model="name"
                            id="name"
                            class="block mt-1 w-full bg-white/80 text-slate-900 border-slate-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400
                                dark:bg-white/80 dark:text-slate-900 dark:border-slate-300"
                            type="text"
                            name="name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Carlos Lagos"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label class="text-slate-700" for="email" :value="__('Correo electrónico')" />
                        <x-text-input
                            wire:model="email"
                            id="email"
                            class="block mt-1 w-full bg-white/80 text-slate-900 border-slate-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400
                                dark:bg-white/80 dark:text-slate-900 dark:border-slate-300"
                            type="email"
                            name="email"
                            required
                            autocomplete="username"
                            placeholder="admin@tusistema.cl"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input
                            wire:model="password"
                            id="password"
                            class="block mt-1 w-full bg-white/80 text-slate-900 border-slate-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400
                                dark:bg-white/80 dark:text-slate-900 dark:border-slate-300"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                        <x-text-input
                            wire:model="password_confirmation"
                            id="password_confirmation"
                            class="block mt-1 w-full bg-white/80 text-slate-900 border-slate-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400
                                dark:bg-white/80 dark:text-slate-900 dark:border-slate-300"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Actions -->
                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 text-base font-semibold">
                            {{ __('Registrarse') }}
                        </x-primary-button>
                    </div>

                    <!-- Back to login -->
                    <div class="text-center text-sm text-slate-600">
                        ¿Ya tienes una cuenta?
                        <a
                            href="{{ route('login') }}"
                            wire:navigate
                            class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors"
                        >
                            Inicia sesión
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

