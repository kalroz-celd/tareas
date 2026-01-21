<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Carta Gantt
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Visualiza el ciclo de vida de proyectos y tareas en líneas de tiempo.
            </p>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('gantt.index', ['view' => 'projects']) }}"
           @class([
               'rounded-xl px-4 py-2 text-sm font-semibold transition-colors duration-300',
               'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $view === 'projects',
               'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' => $view !== 'projects',
           ])>
            Ciclo de vida de proyectos
        </a>
        <a href="{{ route('gantt.index', ['view' => 'project-tasks']) }}"
           @class([
               'rounded-xl px-4 py-2 text-sm font-semibold transition-colors duration-300',
               'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $view === 'project-tasks',
               'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' => $view !== 'project-tasks',
           ])>
            Tareas por proyecto
        </a>
        <a href="{{ route('gantt.index', ['view' => 'tasks']) }}"
           @class([
               'rounded-xl px-4 py-2 text-sm font-semibold transition-colors duration-300',
               'bg-slate-900 text-white dark:bg-white dark:text-slate-900' => $view === 'tasks',
               'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' => $view !== 'tasks',
           ])>
            Todas las tareas
        </a>
    </div>

    @if ($view === 'projects')
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ciclo de vida de proyectos</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Rango general: {{ $projectsTimeline['start']?->format('d/m/Y') ?? '—' }} → {{ $projectsTimeline['end']?->format('d/m/Y') ?? '—' }}</p>

            @if (empty($projectsTimeline['items']))
                <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">No hay proyectos con fechas para mostrar.</div>
            @else
                <div class="mt-6 space-y-4">
                    @foreach ($projectsTimeline['items'] as $item)
                        <div class="grid gap-3 lg:grid-cols-[260px_1fr]">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $item['label'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item['sub_label'] }}</p>
                            </div>
                            <div>
                                <div class="relative h-6 rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="absolute top-0 h-6 rounded-full shadow-sm"
                                         style="left: {{ $item['offset_percent'] }}%; width: {{ $item['duration_percent'] }}%; {{ $item['bar_style'] }}"></div>
                                </div>
                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $item['range_label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @if ($view === 'project-tasks')
        <div class="space-y-6">
            @forelse ($projectTaskTimelines as $projectTimeline)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
                    <div class="flex flex-col gap-1">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $projectTimeline['project'] }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Rango del proyecto: {{ $projectTimeline['project_dates'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Rango de tareas: {{ $projectTimeline['timeline']['start']?->format('d/m/Y') ?? '—' }} → {{ $projectTimeline['timeline']['end']?->format('d/m/Y') ?? '—' }}</p>
                    </div>

                    @if (empty($projectTimeline['timeline']['items']))
                        <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">Este proyecto aún no tiene tareas.</div>
                    @else
                        <div class="mt-6 space-y-4">
                            @foreach ($projectTimeline['timeline']['items'] as $item)
                                <div class="grid gap-3 lg:grid-cols-[260px_1fr]">
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white">{{ $item['label'] }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item['sub_label'] }}</p>
                                    </div>
                                    <div>
                                        <div class="relative h-6 rounded-full bg-slate-100 dark:bg-slate-800">
                                            <div class="absolute top-0 h-6 rounded-full shadow-sm"
                                                 style="left: {{ $item['offset_percent'] }}%; width: {{ $item['duration_percent'] }}%; {{ $item['bar_style'] }}"></div>
                                        </div>
                                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $item['range_label'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">
                    No hay proyectos disponibles.
                </div>
            @endforelse
        </div>
    @endif

    @if ($view === 'tasks')
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Todas las tareas</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Rango general: {{ $allTasksTimeline['start']?->format('d/m/Y') ?? '—' }} → {{ $allTasksTimeline['end']?->format('d/m/Y') ?? '—' }}</p>

            @if (empty($allTasksTimeline['items']))
                <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">No hay tareas para mostrar.</div>
            @else
                <div class="mt-6 space-y-4">
                    @foreach ($allTasksTimeline['items'] as $item)
                        <div class="grid gap-3 lg:grid-cols-[260px_1fr]">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $item['label'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item['sub_label'] }}</p>
                            </div>
                            <div>
                                <div class="relative h-6 rounded-full bg-slate-100 dark:bg-slate-800">
                                    <div class="absolute top-0 h-6 rounded-full shadow-sm"
                                         style="left: {{ $item['offset_percent'] }}%; width: {{ $item['duration_percent'] }}%; {{ $item['bar_style'] }}"></div>
                                </div>
                                <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $item['range_label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>