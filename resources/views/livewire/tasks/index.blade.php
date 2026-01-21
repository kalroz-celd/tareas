<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Tareas
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Lista global de tareas. Incluye el proyecto al que pertenecen.
            </p>
        </div>
    </div>

    {{-- Toast --}}
    @if (session('toast'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-200 transition-colors duration-300">
            {{ session('toast') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="search"
                       placeholder="Título, descripción o proyecto..."
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300" />
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Estado</label>
                <select wire:model.live="status"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="">Todos</option>
                    <option value="todo">Por hacer</option>
                    <option value="doing">En progreso</option>
                    <option value="done">Hecha</option>
                    <option value="blocked">Bloqueada</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Prioridad</label>
                <select wire:model.live="priority"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="">Todas</option>
                    <option value="low">Baja</option>
                    <option value="medium">Media</option>
                    <option value="high">Alta</option>
                    <option value="urgent">Urgente</option>
                </select>
            </div>

            <div class="hidden lg:block"></div>
        </div>
    </div>

    {{-- List --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden transition-colors duration-300">
        {{-- Desktop table --}}
        <div class="hidden md:block">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/50 transition-colors duration-300">
                    <tr class="text-left text-slate-600 dark:text-slate-300">
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('title')">Título</th>
                        <th class="px-4 py-3">Proyecto</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('status')">Estado</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('priority')">Prioridad</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('due_date')">Vence</th>
                        <th class="px-4 py-3 text-right">Ir</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($tasks as $t)
                        <tr class="text-slate-900 dark:text-white cursor-pointer"
                            wire:click="openTaskSummary({{ $t->id }})">
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ $t->title }}</div>
                                @if($t->description)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                        {{ $t->description }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $t->project?->name ?? '—' }}
                                </div>
                                @if($t->project_id)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        #{{ $t->project_id }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->status_badge_class }}"
                                      style="{{ $t->status_badge_style }}">
                                    {{ $t->status_label }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->priority_badge_classes }}"
                                      style="{{ $t->priority_badge_style }}">
                                    {{ $t->priority_label }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                {{ optional($t->due_date)->format('d/m/Y') ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @if($t->project_id)
                                        <a href="{{ route('projects.tasks.index', $t->project_id) }}"
                                            x-on:click.stop
                                            class="rounded-xl border border-indigo-200 px-3 py-1.5 text-xs font-semibold
                                                  text-indigo-700 hover:bg-indigo-50 dark:border-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-500/10 transition-colors duration-300">
                                            Ver proyecto →
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                                No hay tareas con esos filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($tasks as $t)
                <div class="p-4 space-y-2 cursor-pointer"
                     wire:click="openTaskSummary({{ $t->id }})">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-extrabold text-slate-900 dark:text-white">{{ $t->title }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                Proyecto: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $t->project?->name ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->status_badge_class }}"
                                  style="{{ $t->status_badge_style }}">
                                {{ $t->status_label }}
                            </span>
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                transition-colors duration-300" style="{{ $t->priority_badge_style }}">
                                {{ $t->priority_label }}
                            </span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        Vence: {{ optional($t->due_date)->format('d/m/Y') ?? '—' }}
                    </div>

                    @if($t->description)
                        <div class="text-sm text-slate-700 dark:text-slate-200">
                            {{ $t->description }}
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-2 pt-1">
                        @if($t->project_id)
                            <a href="{{ route('projects.tasks.index', $t->project_id) }}"
                                x-on:click.stop
                                class="rounded-xl border border-indigo-200 px-3 py-2 text-xs font-semibold
                                      text-indigo-700 hover:bg-indigo-50 dark:border-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-500/10 transition-colors duration-300">
                                Ver en proyecto →
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-slate-500 dark:text-slate-400">
                    No hay tareas.
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
            {{ $tasks->links() }}
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
