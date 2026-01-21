<div wire:poll.10s="loadData">
    <div class="flex flex-col gap-1 mb-4">
        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
            Panel de control
        </h2>
        <p class="text-sm text-slate-600 dark:text-slate-400">
            Resumen rápido de tus proyectos y tareas.
        </p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($stats as $s)
            <div class="rounded-2xl border border-slate-200 bg-white/70 backdrop-blur p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ $s['label'] }}</div>
                <div class="mt-2 flex items-end justify-between">
                    <div class="text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">{{ $s['value'] }}</div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $s['hint'] }}</div>
                </div>
                <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-slate-900 overflow-hidden">
                    <div class="h-full w-2/3 rounded-full bg-indigo-500/80"></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Tareas recientes --}}
        <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white/70 backdrop-blur shadow-sm dark:border-slate-800 dark:bg-slate-950/60">
            <div class="flex items-center justify-between p-4 border-b border-slate-200/70 dark:border-slate-800/70">
                <div>
                    <div class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Tareas recientes</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Últimas actualizaciones</div>
                </div>

                <a href="{{ route('tasks.index') }}"
                   class="rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                    Ver tareas
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-slate-500 dark:text-slate-400">
                        <tr class="border-b border-slate-200/70 dark:border-slate-800/70">
                            <th class="p-4">Tarea</th>
                            <th class="p-4">Proyecto</th>
                            <th class="p-4">Prioridad</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4">Vence</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-900 dark:text-slate-100">
                        @forelse($recentTasks as $t)
                            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 hover:bg-slate-50/70 dark:hover:bg-slate-900/40 cursor-pointer"
                                wire:click="openTaskSummary({{ $t->id }})">
                                <td class="p-4 font-semibold">{{ $t->title }}</td>
                                <td class="p-4 text-slate-600 dark:text-slate-400">{{ $t->project?->name ?? '—' }}</td>
                                <td class="p-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->priority_badge_classes }}"
                                          style="{{ $t->priority_badge_style }}">
                                        {{ $t->priority_label }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->status_badge_class }}"
                                          style="{{ $t->status_badge_style }}">
                                        {{ $t->status_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-600 dark:text-slate-400">{{ $t->due_date ? $t->due_date->format('d/m/Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">Aún no hay tareas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 flex items-center justify-between">
                <a href="{{ route('tasks.index') }}"
                   class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                    Ver todas las tareas →
                </a>

                {{-- Botón manual por si quieres refrescar al toque --}}
                <button wire:click="loadData"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold
                               text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors duration-300">
                    Actualizar
                </button>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div class="space-y-6">
            {{-- Proyectos --}}
            <div class="rounded-2xl border border-slate-200 bg-white/70 backdrop-blur p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950/60">
                <div class="flex items-center justify-between">
                    <div class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Proyectos</div>
                    <a href="{{ route('projects.index') }}"
                       class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                        Gestionar
                    </a>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($projects as $p)
                        <div class="rounded-xl border border-slate-200 p-3 dark:border-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $p['name'] }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $p['status_badge_class'] }}"
                                          style="{{ $p['status_badge_style'] }}">
                                        {{ $p['status_label'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-100 dark:bg-slate-900 overflow-hidden">
                                <div class="h-full rounded-full bg-sky-500/80" style="width: {{ $p['pct'] }}%"></div>
                            </div>
                            <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                {{ $p['pct'] }}% completo · {{ $p['done'] }}/{{ $p['total'] }} tareas
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-500 dark:text-slate-400">No hay proyectos activos.</div>
                    @endforelse
                </div>
            </div>

            {{-- Actividad --}}
            <div class="rounded-2xl border border-slate-200 bg-white/70 backdrop-blur p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950/60">
                <div class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Actividad</div>
                <div class="mt-4 space-y-3 text-sm">
                    @forelse($activity as $a)
                        <div class="flex gap-3">
                            <div class="mt-1 h-2 w-2 rounded-full
                                {{ $a['type'] === 'done' ? 'bg-emerald-500' : '' }}
                                {{ $a['type'] === 'update' ? 'bg-indigo-500' : '' }}
                                {{ $a['type'] === 'blocked' ? 'bg-rose-500' : '' }}
                            "></div>
                            <div>
                                <div class="font-semibold">{{ $a['title'] }}</div>
                                <div class="text-slate-600 dark:text-slate-400">{{ $a['text'] }} · {{ $a['when'] }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-slate-600 dark:text-slate-400">Sin actividad reciente.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <x-modal name="task-summary" maxWidth="2xl">
        <div class="p-6 space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Resumen de tarea</p>
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                        {{ $selectedTask['title'] ?? 'Tarea' }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Proyecto: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $selectedTask['project_name'] ?? '—' }}</span>
                        @if(!empty($selectedTask['project_id']))
                            <span class="text-xs text-slate-400">#{{ $selectedTask['project_id'] }}</span>
                        @endif
                    </p>
                </div>
                <button type="button"
                        class="rounded-full p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                        x-on:click="$dispatch('close-modal', 'task-summary')">
                    ✕
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $selectedTask['status_badge_class'] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                    {{ $selectedTask['status_label'] ?? '—' }}
                </span>
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $selectedTask['priority_badge_classes'] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                    {{ $selectedTask['priority_label'] ?? '—' }}
                </span>
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                    Vence: {{ $selectedTask['due_date'] ?? '—' }}
                </span>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200">
                {{ $selectedTask['description'] ?? 'Sin descripción disponible.' }}
            </div>

            <div class="flex justify-end">
                <button type="button"
                        class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-800 dark:text-slate-200 dark:hover:bg-slate-800"
                        x-on:click="$dispatch('close-modal', 'task-summary')">
                    Cerrar
                </button>
            </div>
        </div>
    </x-modal>
</div>
