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
           class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors duration-300 {{ $view === 'projects' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
            Ciclo de vida de proyectos
        </a>
        <a href="{{ route('gantt.index', ['view' => 'project-tasks']) }}"
           class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors duration-300 {{ $view === 'project-tasks' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
            Tareas por proyecto
        </a>
        <a href="{{ route('gantt.index', ['view' => 'tasks']) }}"
           class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors duration-300 {{ $view === 'tasks' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800' }}">
            Todas las tareas
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
                    <div class="h-[420px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                        <canvas id="gantt-projects-chart" data-gantt-chart class="h-full w-full"></canvas>
                    </div>
                    @php
                        $projectsChartConfig = [
                            'items' => collect($projectsTimeline['items'])->map(function ($item) {
                                $projectId = $item['id'] ?? null;
                                return [
                                    'label' => $item['label'],
                                    'projectId' => $projectId,
                                    'start' => $item['start']->timestamp * 1000,
                                    'end' => $item['end']->timestamp * 1000,
                                    'range' => $item['range_label'],
                                    'subLabel' => $item['sub_label'],
                                    'clickUrl' => $projectId ? route('gantt.index', ['view' => 'projects', 'project' => $projectId, 'month' => $month ?? null]) : null,
                                ];
                            })->values()->all(),
                            'start' => $projectsTimeline['start']?->timestamp ? $projectsTimeline['start']->timestamp * 1000 : null,
                            'end' => $projectsTimeline['end']?->timestamp ? $projectsTimeline['end']->timestamp * 1000 : null,
                            'interactive' => true,
                        ];
                    @endphp
                    <script type="application/json" data-chart-config-for="gantt-projects-chart">
                        @json($projectsChartConfig)
                    </script>
                </div>
            @endif
        </div>

        @if (!empty($selectedProjectTimeline))
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
                    <script type="application/json" data-chart-config-for="{{ $selectedProjectChartId }}">
                        @json($selectedProjectTimeline['chart_config'])
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
                    @php($projectChartId = 'gantt-project-tasks-chart-'.$loop->index)

                    @if (empty($projectTimeline['timeline']['items']))
                        <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">Este proyecto aún no tiene tareas.</div>
                    @else
                        <div class="mt-6">
                            <div class="h-[380px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                                <canvas id="{{ $projectChartId }}" data-gantt-chart class="h-full w-full"></canvas>
                            </div>
                                    <script type="application/json" data-chart-config-for="{{ $projectChartId }}">
                                @json($projectTimeline['chart_config'])
                            </script>
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
                <div class="mt-6">
                    <div class="h-[420px] rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-950/50">
                        <canvas id="gantt-all-tasks-chart" data-gantt-chart class="h-full w-full"></canvas>
                    </div>
                    <script type="application/json" data-chart-config-for="gantt-all-tasks-chart">
                        @json($allTasksTimeline['chart_config'])
                    </script>
                </div>
            @endif
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    (() => {
        const chartRegistry = new Map();
        const palette = ['#0f172a', '#2563eb', '#16a34a', '#ea580c', '#7c3aed', '#db2777', '#0891b2'];
        const themeTextColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? '#e2e8f0' : '#0f172a';
        const themeGridColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'rgba(148, 163, 184, 0.24)' : 'rgba(148, 163, 184, 0.18)';

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
                            backgroundColor: '#0f172a',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
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
                                color: themeTextColor,
                                font: { size: 12, weight: '600' },
                                callback: (value) => formatDate(value),
                            },
                            grid: {
                                color: themeGridColor,
                            },
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                color: themeTextColor,
                                font: { size: 12, weight: '600' },
                            },
                        },
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
