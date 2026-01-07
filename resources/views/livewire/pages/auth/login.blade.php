<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Left: Brand / Value --}}
        <div class="hidden lg:flex relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white p-10 shadow-lg">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,.55),transparent_40%),radial-gradient(circle_at_80%_30%,rgba(14,165,233,.45),transparent_45%),radial-gradient(circle_at_50%_90%,rgba(34,197,94,.35),transparent_50%)]"></div>

            <div class="relative z-10 flex flex-col justify-between w-full">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-xl bg-white/10 border border-white/10 grid place-content-center">
                            {{-- Check icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xl font-extrabold tracking-tight">Task Admin</div>
                            <div class="text-white/70 text-sm">Control total de proyectos y tareas</div>
                        </div>
                    </div>

                    <div class="mt-10">
                        <h2 class="text-3xl font-black leading-tight tracking-tight">
                            Menos caos. <span class="text-indigo-300">Más entrega.</span>
                        </h2>
                        <p class="mt-4 text-white/75 leading-relaxed">
                            Inicia sesión para gestionar proyectos, priorizar pendientes, medir avance
                            y mantener tu semana bajo control.
                        </p>

                        <div class="mt-8 space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-white/10 border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="font-semibold">Tareas por estado</div>
                                    <div class="text-white/65">Backlog, en progreso, bloqueadas, completadas.</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-white/10 border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="font-semibold">Prioridades claras</div>
                                    <div class="text-white/65">Urgente, alta, media, baja (sin drama).</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-white/10 border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                </span>
                                <div>
                                    <div class="font-semibold">Historial y trazabilidad</div>
                                    <div class="text-white/65">Quién hizo qué y cuándo. Bien ordenadito.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-white/60">
                    Acceso seguro • Sesión persistente opcional • Navegación rápida
                </div>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white/70 backdrop-blur p-6 sm:p-8 shadow-lg">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(99,102,241,.12),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(14,165,233,.10),transparent_40%)]"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
                            Iniciar sesión
                        </h1>
                        <p class="mt-1 text-sm text-slate-600">
                            Entra con tu cuenta de administrador.
                        </p>
                    </div>

                    <div class="lg:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white grid place-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                    </div>
                </div>

                <!-- Session Status -->
                <div class="mt-6">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                </div>

                <form wire:submit="login" class="mt-2 space-y-5">
                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Correo electrónico')" />
                        <x-text-input
                            wire:model="form.email"
                            id="email"
                            class="block mt-1 w-full bg-white/80 text-slate-900 border-slate-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400
                                dark:bg-white/80 dark:text-slate-900 dark:border-slate-300"
                            type="email"
                            name="email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@tusistema.cl"
                        />
                        <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Contraseña')" />
                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md"
                                   href="{{ route('password.request') }}"
                                   wire:navigate>
                                    {{ __('¿Olvisdaste contraseña?') }}
                                </a>
                            @endif
                        </div>

                        <x-text-input
                            wire:model="form.password"
                            id="password"
                            class="block mt-1 w-full bg-white/80 text-slate-900 border-slate-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400
                                dark:bg-white/80 dark:text-slate-900 dark:border-slate-300"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between">
                        <label for="remember" class="inline-flex items-center gap-2 select-none">
                            <input
                                wire:model="form.remember"
                                id="remember"
                                type="checkbox"
                                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                name="remember"
                            >
                            <span class="text-sm text-slate-600">{{ __('Recordarme') }}</span>
                        </label>

                        <span class="text-xs text-slate-500">
                            Tip: usa un gestor de contraseñas 🧠
                        </span>
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 text-base font-semibold">
                            {{ __('Iniciar Sesión') }}
                        </x-primary-button>
                    </div>

                    <div class="text-center text-xs text-slate-500">
                        Al ingresar aceptas las políticas internas del sistema.
                    </div>

                    <div class="mt-6 text-center text-sm text-slate-600">
                        ¿No tienes una cuenta?
                        <a
                            href="{{ route('register') }}"
                            wire:navigate
                            class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors"
                        >
                            Crear cuenta
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
