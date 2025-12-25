<div class="max-w-3xl space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                {{ $project ? 'Editar proyecto' : 'Nuevo proyecto' }}
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ $project ? 'Ajusta la información y guarda cambios.' : 'Completa los datos y crea el proyecto.' }}
            </p>
        </div>

        <a href="{{ route('projects.index') }}"
           class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50
                  dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors duration-300">
            Volver
        </a>
    </div>

    <form wire:submit.prevent="save"
          class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Nombre</label>
                <input type="text" wire:model.live="name"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                              dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Descripción</label>
                <textarea rows="4" wire:model.live="description"
                          class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                                 dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300"></textarea>
                @error('description') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Estado</label>
                <select wire:model.live="status"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                               dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="planning">Planificación</option>
                    <option value="active">Activo</option>
                    <option value="on_hold">En pausa</option>
                    <option value="completed">Completado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
                @error('status') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Prioridad</label>
                <select wire:model.live="priority"
                        class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                               dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                    <option value="low">Baja</option>
                    <option value="medium">Media</option>
                    <option value="high">Alta</option>
                    <option value="urgent">Urgente</option>
                </select>
                @error('priority') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Inicio</label>
                <input type="date" wire:model.live="start_date"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                              dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                @error('start_date') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Vence</label>
                <input type="date" wire:model.live="due_date"
                       class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                              dark:border-slate-700 dark:bg-slate-950 dark:text-white transition-colors duration-300">
                @error('due_date') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                    <input type="checkbox" wire:model.live="is_archived"
                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-700 transition-colors duration-300">
                    Archivado
                </label>
                @error('is_archived') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-2">
            <a href="{{ route('projects.index') }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50
                      dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 transition-colors duration-300">
                Cancelar
            </a>

            <button type="submit"
                    class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                Guardar
            </button>
        </div>
    </form>
</div>
