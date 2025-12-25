<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs text-slate-500 dark:text-slate-400">
                <a class="hover:underline" href="{{ route('projects.tasks.index', $project) }}">Volver a tareas</a>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Edición tarea · {{ $project->name }}
            </h1>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Título</label>
                <input wire:model="title"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300" />
                @error('title') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div class="lg:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Descripción</label>
                <textarea wire:model="description" rows="4"
                          class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300"></textarea>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Estado</label>
                <select wire:model="status"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="todo">Por hacer</option>
                    <option value="doing">En progreso</option>
                    <option value="done">Hecha</option>
                    <option value="blocked">Bloqueada</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Prioridad</label>
                <select wire:model="priority"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="low">Baja</option>
                    <option value="medium">Media</option>
                    <option value="high">Alta</option>
                    <option value="urgent">Urgente</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Fecha de vencimiento</label>
                <input type="date" wire:model="due_date"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300" />
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('projects.tasks.index', $project) }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors duration-300">
                Cancelar
            </a>

            <button wire:click="save"
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                Actualizar
            </button>
        </div>
    </div>
</div>
