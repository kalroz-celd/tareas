<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('projects.index') }}" class="hover:underline">Proyectos</a>
                <span class="mx-2">/</span>
                <span class="text-slate-700 dark:text-slate-200">{{ $project->name }}</span>
                <span class="mx-2">/</span>
                <span class="text-slate-700 dark:text-slate-200">Tareas</span>
            </div>

            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Tareas · {{ $project->name }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Administra las tareas de este proyecto.
            </p>
        </div>

        <a href="{{ route('projects.tasks.create', $project) }}"
           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500">
            + Nueva tarea
        </a>
    </div>

    {{-- Toast --}}
    @if (session('toast'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-200 transition-colors duration-300">
            {{ session('toast') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="search"
                       placeholder="Título o descripción..."
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
        </div>
    </div>

    {{-- List --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden transition-colors duration-300">
        <div class="hidden md:block">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/50 transition-colors duration-300">
                    <tr class="text-left text-slate-600 dark:text-slate-300">
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('title')">Título</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('status')">Estado</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('priority')">Prioridad</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('due_date')">Vence</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($tasks as $t)
                        <tr class="text-slate-900 dark:text-white">
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ $t->title }}</div>
                                @if($t->description)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                        {{ $t->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->status_badge_class }}">
                                    {{ $t->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->priority_badge_classes }}">
                                    {{ $t->priority_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ optional($t->due_date)->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('projects.tasks.edit', [$project, $t]) }}"
                                       class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white
                                              hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200 transition-colors duration-300">
                                        Editar
                                    </a>

                                    <button
                                        onclick="confirm('¿Eliminar esta tarea?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $t->id }})"
                                        class="rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-500 transition-colors">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                                No hay tareas con esos filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($tasks as $t)
                <div class="p-4 space-y-2">
                    <div class="font-extrabold text-slate-900 dark:text-white">{{ $t->title }}</div>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $t->status_badge_class }}">
                            {{ $t->status_label }}
                        </span>
                        · Prioridad:
                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold align-middle transition-colors duration-300 {{ $t->priority_badge_classes }}">
                            {{ $t->priority_label }}
                        </span>
                        · Vence: {{ optional($t->due_date)->format('d/m/Y') ?? '—' }}
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('projects.tasks.edit', [$project, $t]) }}"
                           class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white
                                  hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200 transition-colors duration-300">
                            Editar
                        </a>

                        <button
                            onclick="confirm('¿Eliminar esta tarea?') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $t->id }})"
                            class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-500 transition-colors">
                            Eliminar
                        </button>
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
</div>
