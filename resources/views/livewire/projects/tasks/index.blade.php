<div class="space-y-4" x-data="{
    showConfirm: false,
    confirmTaskId: null,
    showDeleted: false,
    confirmDeletion(id) {
        this.confirmTaskId = id;
        this.showConfirm = true;
    },
    proceedDeletion() {
        if (!this.confirmTaskId) return;
        this.$wire.delete(this.confirmTaskId);
        this.showConfirm = false;
    },
    cancelDeletion() {
        this.showConfirm = false;
        this.confirmTaskId = null;
    },
    init() {
        window.addEventListener('task-deleted', () => {
            this.showDeleted = true;
            this.confirmTaskId = null;
            setTimeout(() => this.showDeleted = false, 1000);
        });
    }
}">
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

    {{-- Confirm deletion modal --}}
    <div
        x-show="showConfirm"
        x-transition.opacity.duration.150ms
        class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
        <div
            x-show="showConfirm"
            x-transition.scale.origin.center.duration.200ms
            class="w-[360px] rounded-2xl bg-white px-8 py-7 text-center text-slate-800 shadow-2xl ring-1 ring-slate-200 dark:bg-slate-900 dark:text-white dark:ring-slate-700">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100 text-amber-500 dark:bg-amber-900/40 dark:text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.981-1.744 2.981H4.42c-1.53 0-2.493-1.647-1.743-2.98l5.58-9.92zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-.293-6.707a1 1 0 00-1.414 1.414L9.586 10a1 1 0 001.414 0l.293-.293a1 1 0 10-1.414-1.414L10 8.586l-.293-.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <h2 class="mt-4 text-xl font-bold">¿Estás seguro?</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No podrás revertir esta acción.</p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button
                    type="button"
                    @click="cancelDeletion"
                    class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Cancelar
                </button>
                <button
                    type="button"
                    @click="proceedDeletion"
                    class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-rose-500">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>

    {{-- Deleted modal --}}
    <div
        x-show="showDeleted"
        x-transition.opacity.duration.150ms
        class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
        <div
            x-show="showDeleted"
            x-transition.scale.origin.center.duration.200ms
            class="w-[360px] rounded-2xl bg-white px-8 py-7 text-center text-slate-800 shadow-2xl ring-1 ring-slate-200 dark:bg-slate-900 dark:text-white dark:ring-slate-700">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-8 w-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                </svg>
            </div>
            <h2 class="mt-4 text-xl font-bold">¡Eliminada!</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">La tarea se eliminó correctamente.</p>
            <div class="mt-6">
                <button
                    type="button"
                    @click="showDeleted = false"
                    class="rounded-lg bg-indigo-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-indigo-600">
                    Aceptar
                </button>
            </div>
        </div>
    </div>

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
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 transition-colors duration-300">
                                    {{ $t->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $t->priority }}</td>
                            <td class="px-4 py-3">{{ optional($t->due_date)->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('projects.tasks.edit', [$project, $t]) }}"
                                       class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white
                                              hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200 transition-colors duration-300">
                                        Editar
                                    </a>

                                    <button
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
                    <div class="text-xs text-slate-500 dark:text-slate-400">
                        Estado: {{ $t->status }} · Prioridad: {{ $t->priority }} · Vence: {{ optional($t->due_date)->format('d/m/Y') ?? '—' }}
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('projects.tasks.edit', [$project, $t]) }}"
                           class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white
                                  hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200 transition-colors duration-300">
                            Editar
                        </a>

                        <button
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
