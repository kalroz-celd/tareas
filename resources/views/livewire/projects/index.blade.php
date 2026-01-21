<div class="space-y-4">
    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Proyectos
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Gestiona tus proyectos con búsqueda, filtros y archivado.
            </p>
        </div>

        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500">
            + Nuevo proyecto
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
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="search"
                       placeholder="Nombre o descripción..."
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300" />
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Estado</label>
                <select wire:model.live="status"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="">Todos</option>
                    <option value="planning">Planificación</option>
                    <option value="active">Activo</option>
                    <option value="on_hold">En pausa</option>
                    <option value="completed">Completado</option>
                    <option value="cancelled">Cancelado</option>
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

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Archivados</label>
                <select wire:model.live="archived"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="">Todos</option>
                    <option value="0">Solo activos</option>
                    <option value="1">Solo archivados</option>
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
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('name')">Nombre</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('status')">Estado</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('priority')">Prioridad</th>
                        <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('due_date')">Vence</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($projects as $p)
                        <tr class="text-slate-900 dark:text-white cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors duration-300"
                            onclick="window.location='{{ route('projects.tasks.index', $p) }}'">
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ $p->name }}</div>
                                @if($p->description)
                                    <div class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                        {{ $p->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $p->status_badge_class }}">
                                    {{ $p->status_label }}
                                </span>
                                @if($p->is_archived)
                                    <span class="ml-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200 transition-colors duration-300">
                                        archivado
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $p->priority_badge_classes }}">
                                    {{ $p->priority_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ optional($p->due_date)->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('projects.tasks.index', $p) }}"
                                    onclick="event.stopPropagation()"
                                    class="rounded-xl border border-indigo-200 px-3 py-1.5 text-xs font-semibold
                                            text-indigo-700 hover:bg-indigo-50 dark:border-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-500/10 transition-colors duration-300">
                                        Tareas
                                    </a>
                                    <button wire:click="toggleArchive({{ $p->id }})"
                                            class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold
                                                   text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors duration-300">
                                        {{ $p->is_archived ? 'Reactivar' : 'Archivar' }}
                                    </button>

                                    <a href="{{ route('projects.edit', $p) }}"
                                       class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white
                                              hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200 transition-colors duration-300">
                                        Editar
                                    </a>

                                    <button
                                        onclick="confirm('¿Eliminar este proyecto?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $p->id }})"
                                        class="rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-500 transition-colors">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                                No hay proyectos con esos filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($projects as $p)
                <div class="p-4 space-y-2">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-extrabold text-slate-900 dark:text-white">{{ $p->name }}</div>
                            <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold transition-colors duration-300 {{ $p->status_badge_class }}">
                                    {{ $p->status_label }}
                                </span>
                                · Prioridad:
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold align-middle transition-colors duration-300 {{ $p->priority_badge_classes }}">
                                    {{ $p->priority_label }}
                                </span>
                            </div>
                        </div>
                        @if($p->is_archived)
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200 transition-colors duration-300">
                                archivado
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('projects.tasks.index', $p) }}"
                            class="rounded-xl border border-indigo-200 px-3 py-2 text-xs font-semibold
                                    text-indigo-700 hover:bg-indigo-50 dark:border-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-500/10 transition-colors duration-300">
                            Tareas
                        </a>
                        <button wire:click="toggleArchive({{ $p->id }})"
                                class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold
                                       text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors duration-300">
                            {{ $p->is_archived ? 'Reactivar' : 'Archivar' }}
                        </button>

                        <a href="{{ route('projects.edit', $p) }}"
                           class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white
                                  hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200 transition-colors duration-300">
                            Editar
                        </a>

                        <button
                            onclick="confirm('¿Eliminar este proyecto?') || event.stopImmediatePropagation()"
                            wire:click="delete({{ $p->id }})"
                            class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-500 transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-slate-500 dark:text-slate-400">
                    No hay proyectos.
                </div>
            @endforelse
        </div>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
            {{ $projects->links() }}
        </div>
    </div>
</div>
