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

    <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
        <span>Mes seleccionado:</span>
        <span class="font-semibold text-slate-700 dark:text-slate-200">
            {{ $selectedMonthLabel ?? '—' }}
        </span>
        <a href="{{ route('gantt.index', ['view' => $view]) }}"
           class="rounded-lg border border-slate-200 px-2 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
            Limpiar mes
        </a>
    </div>

    @if ($view === 'projects')
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ciclo de vida de proyectos</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Rango general: {{ $projectsTimeline['start']?->format('d/m/Y') ?? '—' }} → {{ $projectsTimeline['end']?->format('d/m/Y') ?? '—' }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Haz click sobre una barra de proyecto para ver su Gantt de tareas.</p>    
            
            @if (empty($projectsTimeline['items']))
                <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">No hay proyectos con fechas para mostrar.</div>
            @else
                <div class="mt-6">
                    <div class="relative h-10 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        @foreach ($projectsTimeline['months'] as $monthSegment)
                            <a href="{{ route('gantt.index', ['view' => 'projects', 'month' => $monthSegment['key']]) }}"
                               class="absolute top-1 flex h-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                               style="left: {{ $monthSegment['offset_percent'] }}%; width: {{ $monthSegment['duration_percent'] }}%;">
                                {{ $monthSegment['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6">
                    <div class="h-[420px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                        <canvas id="gantt-projects-chart" data-gantt-chart class="h-full w-full"></canvas>
                    </div>
                    @php
                        $projectsChartPayload = [
                            'items' => collect($projectsTimeline['items'])->map(function ($item) {
                                return [
                                    'label' => $item['label'],
                                    'projectId' => $item['id'],
                                    'start' => $item['start']->timestamp * 1000,
                                    'end' => $item['end']->timestamp * 1000,
                                    'range' => $item['range_label'],
                                    'subLabel' => $item['sub_label'],
                                    'clickUrl' => route('gantt.index', ['view' => 'projects', 'project' => $item['id'], 'month' => $month]),
                                ];
                            })->values()->all(),
                            'start' => $projectsTimeline['start']?->timestamp ? $projectsTimeline['start']->timestamp * 1000 : null,
                            'end' => $projectsTimeline['end']?->timestamp ? $projectsTimeline['end']->timestamp * 1000 : null,
                            'interactive' => true,
                        ];
                    @endphp
                    <script type="application/json" data-chart-config-for="gantt-projects-chart">
                        @json($projectsChartPayload)
                    </script>
                </div>
                @if ($projectsTimeline['month_breakdown']['month'])
                    <div class="mt-8">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ $projectsTimeline['month_breakdown']['month']->translatedFormat('F Y') }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Haz click en los meses para ver el detalle diario.</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($projectsTimeline['month_breakdown']['days'] as $day)
                                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                                    <div class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                        {{ $day['date']->format('d/m') }}
                                    </div>
                                    @if (empty($day['tasks']))
                                        <div class="mt-2 text-xs text-slate-400">Sin tareas.</div>
                                    @else
                                        <div class="mt-2 space-y-1">
                                            @foreach ($day['tasks'] as $task)
                                                <div class="rounded-full px-2 py-1 text-[11px] font-semibold" style="{{ $task['style'] }}">
                                                    {{ $task['label'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>

        @if ($selectedProjectTimeline)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                            Tareas del proyecto: {{ $selectedProjectTimeline['project'] }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $selectedProjectTimeline['timeline']['start']?->format('d/m/Y') ?? '—' }} → {{ $selectedProjectTimeline['timeline']['end']?->format('d/m/Y') ?? '—' }}
                        </p>
                    </div>
                    <a href="{{ route('gantt.index', ['view' => 'projects', 'month' => $month]) }}"
                       class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                        Cerrar detalle
                    </a>
                </div>

                @if (empty($selectedProjectTimeline['timeline']['items']))
                    <div class="mt-4 text-sm text-slate-500 dark:text-slate-400">Este proyecto no tiene tareas registradas.</div>
                @else
                    @php($selectedProjectChartId = 'gantt-selected-project-chart-'.$selectedProjectTimeline['project_id'])
                    <div class="mt-4 h-[380px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                        <canvas id="{{ $selectedProjectChartId }}" data-gantt-chart class="h-full w-full"></canvas>
                    </div>
                    @php
                        $selectedProjectChartPayload = [
                            'items' => collect($selectedProjectTimeline['timeline']['items'])->map(function ($item) {
                                return [
                                    'label' => $item['label'],
                                    'start' => $item['start']->timestamp * 1000,
                                    'end' => $item['end']->timestamp * 1000,
                                    'range' => $item['range_label'],
                                    'subLabel' => $item['sub_label'],
                                ];
                            })->values()->all(),
                            'start' => $selectedProjectTimeline['timeline']['start']?->timestamp ? $selectedProjectTimeline['timeline']['start']->timestamp * 1000 : null,
                            'end' => $selectedProjectTimeline['timeline']['end']?->timestamp ? $selectedProjectTimeline['timeline']['end']->timestamp * 1000 : null,
                        ];
                    @endphp
                    <script type="application/json" data-chart-config-for="{{ $selectedProjectChartId }}">
                        @json($selectedProjectChartPayload)
                    </script>
                @endif
            </div>
        @endif
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
                        <div class="mt-6">
                            <div class="relative h-10 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                                @foreach ($projectTimeline['months'] as $monthSegment)
                                    <a href="{{ route('gantt.index', ['view' => 'project-tasks', 'month' => $monthSegment['key']]) }}"
                                       class="absolute top-1 flex h-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                                       style="left: {{ $monthSegment['offset_percent'] }}%; width: {{ $monthSegment['duration_percent'] }}%;">
                                        {{ $monthSegment['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-6">
                            @php($projectChartId = 'gantt-project-tasks-chart-'.$loop->index)
                            <div class="h-[380px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                                <canvas id="{{ $projectChartId }}" data-gantt-chart class="h-full w-full"></canvas>
                            </div>
                            @php
                                $projectTasksChartPayload = [
                                    'items' => collect($projectTimeline['timeline']['items'])->map(function ($item) {
                                        return [
                                            'label' => $item['label'],
                                            'start' => $item['start']->timestamp * 1000,
                                            'end' => $item['end']->timestamp * 1000,
                                            'range' => $item['range_label'],
                                            'subLabel' => $item['sub_label'],
                                        ];
                                    })->values()->all(),
                                    'start' => $projectTimeline['timeline']['start']?->timestamp ? $projectTimeline['timeline']['start']->timestamp * 1000 : null,
                                    'end' => $projectTimeline['timeline']['end']?->timestamp ? $projectTimeline['timeline']['end']->timestamp * 1000 : null,
                                ];
                            @endphp
                            <script type="application/json" data-chart-config-for="{{ $projectChartId }}">
                                @json($projectTasksChartPayload)
                            </script>
                        </div>
                        @if ($projectTimeline['month_breakdown']['month'])
                            <div class="mt-8">
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                    {{ $projectTimeline['month_breakdown']['month']->translatedFormat('F Y') }}
                                </h3>
                                <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                    @foreach ($projectTimeline['month_breakdown']['days'] as $day)
                                        <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                                {{ $day['date']->format('d/m') }}
                                            </div>
                                            @if (empty($day['tasks']))
                                                <div class="mt-2 text-xs text-slate-400">Sin tareas.</div>
                                            @else
                                                <div class="mt-2 space-y-1">
                                                    @foreach ($day['tasks'] as $task)
                                                        <div class="rounded-full px-2 py-1 text-[11px] font-semibold" style="{{ $task['style'] }}">
                                                            {{ $task['label'] }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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
                <div class="mt-6">
                    <div class="relative h-10 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        @foreach ($allTasksTimeline['months'] as $monthSegment)
                            <a href="{{ route('gantt.index', ['view' => 'tasks', 'month' => $monthSegment['key']]) }}"
                               class="absolute top-1 flex h-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
                               style="left: {{ $monthSegment['offset_percent'] }}%; width: {{ $monthSegment['duration_percent'] }}%;">
                                {{ $monthSegment['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6">
                    <div class="h-[420px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                        <canvas id="gantt-all-tasks-chart" data-gantt-chart class="h-full w-full"></canvas>
                    </div>
                    @php
                        $allTasksChartPayload = [
                            'items' => collect($allTasksTimeline['items'])->map(function ($item) {
                                return [
                                    'label' => $item['label'],
                                    'start' => $item['start']->timestamp * 1000,
                                    'end' => $item['end']->timestamp * 1000,
                                    'range' => $item['range_label'],
                                    'subLabel' => $item['sub_label'],
                                ];
                            })->values()->all(),
                            'start' => $allTasksTimeline['start']?->timestamp ? $allTasksTimeline['start']->timestamp * 1000 : null,
                            'end' => $allTasksTimeline['end']?->timestamp ? $allTasksTimeline['end']->timestamp * 1000 : null,
                        ];
                    @endphp
                    <script type="application/json" data-chart-config-for="gantt-all-tasks-chart">
                        @json($allTasksChartPayload)
                    </script>
                </div>
                @if ($allTasksTimeline['month_breakdown']['month'])
                    <div class="mt-8">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ $allTasksTimeline['month_breakdown']['month']->translatedFormat('F Y') }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Detalle diario de tareas.</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($allTasksTimeline['month_breakdown']['days'] as $day)
                                <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                                    <div class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                        {{ $day['date']->format('d/m') }}
                                    </div>
                                    @if (empty($day['tasks']))
                                        <div class="mt-2 text-xs text-slate-400">Sin tareas.</div>
                                    @else
                                        <div class="mt-2 space-y-1">
                                            @foreach ($day['tasks'] as $task)
                                                <div class="rounded-full px-2 py-1 text-[11px] font-semibold" style="{{ $task['style'] }}">
                                                    {{ $task['label'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    (() => {
        const chartRegistry = new Map();
        const palette = ['#0f172a', '#2563eb', '#16a34a', '#ea580c', '#7c3aed', '#db2777', '#0891b2'];

        const formatDate = (timestamp) => new Intl.DateTimeFormat('es-CL', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        }).format(new Date(timestamp));

        const buildChart = (canvas) => {
            const configNode = document.querySelector(`script[data-chart-config-for="${canvas.id}"]`);
            if (!configNode) return;

            const payload = JSON.parse(configNode.textContent ?? '{}');
            const items = payload.items ?? [];
            if (!items.length) return;

            const data = items.map((item, index) => ({
                x: [item.start, item.end],
                y: item.label,
                range: item.range,
                subLabel: item.subLabel,
                backgroundColor: palette[index % palette.length],
                clickUrl: item.clickUrl ?? null,
            }));

            const minX = payload.start ?? Math.min(...items.map((item) => item.start));
            const maxX = payload.end ?? Math.max(...items.map((item) => item.end));
            const padding = 1000 * 60 * 60 * 24;

            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    datasets: [{
                        label: 'Planificación',
                        data,
                        parsing: {
                            xAxisKey: 'x',
                            yAxisKey: 'y',
                        },
                        borderRadius: 8,
                        borderSkipped: false,
                        backgroundColor: data.map((point) => point.backgroundColor),
                        barThickness: 16,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (context) => context[0]?.raw?.y ?? '',
                                label: (context) => context.raw?.subLabel ?? '',
                                afterLabel: (context) => context.raw?.range ?? '',
                            },
                        },
                    },
                    scales: {
                        x: {
                            type: 'linear',
                            min: minX - padding,
                            max: maxX + padding,
                            ticks: {
                                callback: (value) => formatDate(value),
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.18)',
                            },
                        },
                        y: {
                            grid: { display: false },
                        },
                        onClick: (_, elements) => {
                            if (!payload.interactive || !elements.length) return;

                            const firstPoint = elements[0];
                            const point = chart.data.datasets[firstPoint.datasetIndex]?.data?.[firstPoint.index];

                            if (point?.clickUrl) {
                                window.location.href = point.clickUrl;
                            }
                        },
                    },
                },
            });

            chartRegistry.set(canvas.id, chart);
        };

        const mountCharts = () => {
            document.querySelectorAll('[data-gantt-chart]').forEach((canvas) => {
                if (!canvas.id || chartRegistry.has(canvas.id)) return;
                buildChart(canvas);
            });
        };

        const destroyCharts = () => {
            chartRegistry.forEach((chart) => chart.destroy());
            chartRegistry.clear();
        };

        document.addEventListener('livewire:navigate', destroyCharts);
        document.addEventListener('livewire:navigated', mountCharts);
        mountCharts();
    })();
</script>