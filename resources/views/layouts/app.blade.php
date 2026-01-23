<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Evita “flash” entre claro/oscuro --}}
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = stored ?? (prefersDark ? 'dark' : 'light');

            if (theme === 'dark') document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        })();
    </script>

    @livewireStyles
    <style>[x-cloak]{display:none !important;}</style>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <livewire:layout.navigation />

        {{-- Contenido principal --}}
        <main class="lg:pl-72 flex-1">
            {{-- Header opcional --}}
            @php($hasNotifications = \App\Livewire\Notifications\Header::hasNotifications())
            @if (isset($header) || $hasNotifications)
                <header class="sticky top-0 z-30 border-b border-slate-200/70 bg-white/70 backdrop-blur dark:border-slate-800/70 dark:bg-slate-950/60">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 space-y-4">
                        @if (isset($header))
                            {{ $header }}
                        @endif

                        @if ($hasNotifications)
                            <livewire:notifications.header />
                        @endif
                    </div>
                </header>
            @endif

            <div class="px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot }}
            </div>
        </main>
        <footer class="lg:pl-72 border-t border-slate-200/70 bg-white/70 text-slate-600 dark:border-slate-800/70 dark:bg-slate-950/60 dark:text-slate-300">
            <div class="px-4 sm:px-6 lg:px-8 py-4 text-sm">
                Todos los derechos reservados &copy; {{ date('Y') }}
                <a class="font-medium text-slate-700 underline-offset-4 hover:underline dark:text-slate-200" href="https://celd.cl" target="_blank">
                    CELD
                </a>
            </div>
        </footer>
    </div>

    @livewireScripts

    <script>
        document.addEventListener('livewire:navigated', () => {
            Livewire.dispatch('close-all-modals');
        });
    </script>
</body>
</html>
