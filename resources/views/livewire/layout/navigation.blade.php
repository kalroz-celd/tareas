<div
    x-data="{
        mobileOpen: false,
        userOpen: false,
        toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        },
        closeAll() { this.mobileOpen = false; this.userOpen = false; }
    }"
    @keydown.escape.window="closeAll()"
>
    {{-- Topbar móvil --}}
    <div class="lg:hidden sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur dark:border-slate-800/70 dark:bg-slate-950/70">
        <div class="flex items-center justify-between px-4 py-3">
            <button
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 shadow-sm
                       hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200"
                @click="mobileOpen = true"
                aria-label="Abrir menú"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <a href="{{ route('dashboard') }}" class="font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                Task Admin
            </a>

            <div class="flex items-center gap-2">
                <button
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 shadow-sm
                           hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200"
                    @click="toggleTheme()"
                    aria-label="Cambiar tema"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0-1.414-1.414M7.05 7.05 5.636 5.636"/>
                        <circle cx="12" cy="12" r="4"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>

                <button
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 shadow-sm
                           hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200"
                    @click="userOpen = !userOpen"
                    aria-label="Menú usuario"
                >
                    <span class="text-sm font-semibold">
                        {{ auth()->user()->name ?? 'Usuario' }}
                    </span>
                </button>
            </div>
        </div>

        {{-- Dropdown usuario móvil --}}
        <div x-show="userOpen" x-transition @click.outside="userOpen = false"
             class="px-4 pb-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="text-xs text-slate-500 dark:text-slate-400">Sesión</div>
                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ auth()->user()->email ?? '' }}
                </div>

                <div class="mt-3 border-t border-slate-200 pt-3 dark:border-slate-800">
                    <form method="POST" action="{{ __('Log Out') }}">
                        @csrf
                        <button class="w-full rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Overlay móvil --}}
    <div
        class="lg:hidden fixed inset-0 z-40 bg-slate-900/50"
        x-show="mobileOpen"
        x-transition.opacity
        @click="mobileOpen = false"
        style="display:none;"
    ></div>

    {{-- Drawer móvil --}}
    <aside
        class="lg:hidden fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto bg-white p-4 shadow-xl dark:bg-slate-950"
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        style="display:none;"
    >
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                Task Admin
            </a>
            <button
                class="rounded-xl border border-slate-200 bg-white p-2 text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200"
                @click="mobileOpen = false"
                aria-label="Cerrar menú"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="mt-6 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold
                      text-slate-900 hover:bg-slate-100 dark:text-slate-100 dark:hover:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                Dashboard
            </a>

            <a href="{{ route('projects.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold
                      text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                Proyectos
            </a>

            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold
                      text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                Tareas
            </a>

            <a href="{{ route('clients.index') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold
                      text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                Clientes
            </a>

            <a href="#"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold
                      text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                Reportes
            </a>

            <a href="#"
               class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold
                      text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                Ajustes
            </a>
        </nav>

        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
            <button
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50
                       dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                @click="toggleTheme()"
            >
                Cambiar tema
            </button>

            <form class="mt-3" method="POST" action="{{ __('Log Out') }}">
                @csrf
                <button class="w-full rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Sidebar desktop --}}
    <aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:w-72 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-slate-200 bg-white px-4 py-6 dark:border-slate-800 dark:bg-slate-950">
            <div class="flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-indigo-600 to-sky-500"></div>
                    <div>
                        <div class="text-sm font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Task Admin</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Gestión de tareas</div>
                    </div>
                </a>

                <button
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 shadow-sm
                           hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200"
                    @click="toggleTheme()"
                    title="Cambiar tema"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 3v2m0 14v2m9-9h-2M5 12H3"/>
                        <circle cx="12" cy="12" r="4"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </button>
            </div>

            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-semibold
                                  bg-slate-100 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
                            <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('projects.index') }}"
                           class="flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-semibold
                                  text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                            <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            Proyectos
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('tasks.index') }}"
                           class="flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-semibold
                                  text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                            <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            Tareas
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('clients.index') }}"
                            class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold
                                    text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                            <span>👤</span>
                            <span>Clientes</span>
                        </a>
                    </li>

                    <li>
                        <a href="#"
                           class="flex items-center gap-x-3 rounded-xl px-3 py-2 text-sm font-semibold
                                  text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900">
                            <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            Reportes
                        </a>
                    </li>

                    <li class="mt-4">
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 px-3">
                            Cuenta
                        </div>
                    </li>

                    <li>
                        <button
                            @click="userOpen = !userOpen"
                            class="w-full flex items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold
                                   text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-900"
                        >
                            <span class="truncate">{{ auth()->user()->name ?? 'Usuario' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="userOpen" x-transition @click.outside="userOpen = false" class="mt-2 px-2">
                            <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="text-xs text-slate-500 dark:text-slate-400">Email</div>
                                <div class="text-sm font-semibold text-slate-900 dark:text-slate-100 break-all">
                                    {{ auth()->user()->email ?? '' }}
                                </div>

                                <form class="mt-3" method="POST" action="{{ __('Log Out') }}">
                                    @csrf
                                    <button class="w-full rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</div>
