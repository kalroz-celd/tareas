<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Gestor de contraseñas
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Guarda accesos de proyectos y datos personales de forma ordenada.
            </p>
        </div>
    </div>

    @if (session('toast'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-200 transition-colors duration-300">
            {{ session('toast') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ $editingId ? 'Editar credencial' : 'Nueva credencial' }}
                    </h2>
                    @if($editingId)
                        <button type="button"
                                wire:click="resetForm"
                                class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                            Cancelar
                        </button>
                    @endif
                </div>

                <div class="mt-4 space-y-3" x-data="{ category: @entangle('entryCategory') }">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Título</label>
                        <input type="text"
                               wire:model.defer="title"
                               class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                        @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Tipo</label>
                        <select wire:model.defer="entryCategory"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="personal">Personal</option>
                            <option value="project">Proyecto</option>
                        </select>
                        @error('entryCategory') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="category === 'project'">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Proyecto</label>
                        <select wire:model.defer="projectId"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Selecciona un proyecto</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('projectId') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Usuario / Email</label>
                        <input type="text"
                               wire:model.defer="username"
                               class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                        @error('username') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Contraseña</label>
                        <input type="password"
                               wire:model.defer="secret"
                               class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                        @error('secret') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">URL</label>
                        <input type="text"
                               wire:model.defer="url"
                               placeholder="https://..."
                               class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                        @error('url') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Notas</label>
                        <textarea wire:model.defer="notes"
                                  rows="3"
                                  class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white"></textarea>
                        @error('notes') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="button"
                            wire:click="save"
                            class="w-full rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-500">
                        {{ $editingId ? 'Guardar cambios' : 'Guardar credencial' }}
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Buscar</label>
                        <input type="text"
                               wire:model.live.debounce.400ms="search"
                               placeholder="Título, usuario, URL o notas"
                               class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Tipo</label>
                        <select wire:model.live="filterCategory"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Todos</option>
                            <option value="personal">Personal</option>
                            <option value="project">Proyecto</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-300">Proyecto</label>
                        <select wire:model.live="filterProject"
                                class="mt-1 w-full rounded-xl border-slate-200 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Todos</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900 overflow-hidden transition-colors duration-300">
                <div class="hidden md:block">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-950/50 transition-colors duration-300">
                            <tr class="text-left text-slate-600 dark:text-slate-300">
                                <th class="px-4 py-3 cursor-pointer" wire:click="sortBy('title')">Título</th>
                                <th class="px-4 py-3">Tipo</th>
                                <th class="px-4 py-3">Proyecto</th>
                                <th class="px-4 py-3">Usuario</th>
                                <th class="px-4 py-3">Secreto</th>
                                <th class="px-4 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($entries as $entry)
                                <tr class="text-slate-900 dark:text-white" wire:key="password-entry-{{ $entry->id }}">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold">{{ $entry->title }}</div>
                                        @if($entry->url)
                                            <a href="{{ $entry->url }}" target="_blank" rel="noopener"
                                               class="text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                                                {{ $entry->url }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                            {{ $entry->category === 'project' ? 'Proyecto' : 'Personal' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-slate-700 dark:text-slate-200">
                                            {{ $entry->project?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-slate-700 dark:text-slate-200">
                                            {{ $entry->username ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div x-data="{ reveal: false, secret: @js($entry->secret) }" class="flex items-center gap-2">
                                            <span class="font-mono text-xs" x-text="reveal ? secret : '••••••••'"></span>
                                            <button type="button"
                                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-300"
                                                    @click="reveal = !reveal">
                                                <span x-text="reveal ? 'Ocultar' : 'Mostrar'"></span>
                                            </button>
                                            <button type="button"
                                                    class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-300"
                                                    @click="navigator.clipboard.writeText(secret)">
                                                Copiar
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                    wire:click="edit({{ $entry->id }})"
                                                    class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white
                                                           hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                                                Editar
                                            </button>
                                            <button type="button"
                                                    onclick="confirm('¿Eliminar esta credencial?') || event.stopImmediatePropagation()"
                                                    wire:click="delete({{ $entry->id }})"
                                                    class="rounded-xl bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-500">
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                                        No hay credenciales con esos filtros.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($entries as $entry)
                        <div class="p-4 space-y-3" wire:key="password-entry-mobile-{{ $entry->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-extrabold text-slate-900 dark:text-white">{{ $entry->title }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $entry->category === 'project' ? 'Proyecto' : 'Personal' }}
                                        @if($entry->project?->name)
                                            · {{ $entry->project->name }}
                                        @endif
                                    </div>
                                    @if($entry->url)
                                        <a href="{{ $entry->url }}" target="_blank" rel="noopener"
                                           class="text-xs text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                                            {{ $entry->url }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                Usuario: <span class="text-slate-700 dark:text-slate-200">{{ $entry->username ?? '—' }}</span>
                            </div>

                            <div x-data="{ reveal: false, secret: @js($entry->secret) }" class="flex items-center gap-2 text-xs">
                                <span class="font-mono" x-text="reveal ? secret : '••••••••'"></span>
                                <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-300"
                                        @click="reveal = !reveal">
                                    <span x-text="reveal ? 'Ocultar' : 'Mostrar'"></span>
                                </button>
                                <button type="button"
                                        class="font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-300"
                                        @click="navigator.clipboard.writeText(secret)">
                                    Copiar
                                </button>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                        wire:click="edit({{ $entry->id }})"
                                        class="rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white
                                               hover:bg-slate-700 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                                    Editar
                                </button>
                                <button type="button"
                                        onclick="confirm('¿Eliminar esta credencial?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $entry->id }})"
                                        class="rounded-xl bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-500">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-slate-500 dark:text-slate-400">
                            No hay credenciales.
                        </div>
                    @endforelse
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
                    {{ $entries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
