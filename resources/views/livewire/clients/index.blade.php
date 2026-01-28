<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                Clientes
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                Gestiona clientes y revisa sus proyectos.
            </p>
        </div>

        <div class="flex gap-2">
            <input
                wire:model.live="search"
                type="text"
                placeholder="Buscar por nombre o email…"
                class="w-64 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm
                       shadow-sm outline-none focus:ring-2 focus:ring-slate-300
                       dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
            />

            <button
                wire:click="create"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white
                       shadow-sm hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white"
            >
                + Nuevo cliente
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900
                    dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-100">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-950/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Cliente
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Contacto
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Proyectos asociados
                        </th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-950/40">
                            <td class="px-4 py-3">
                                <a href="{{ route('clients.show', $client) }}"
                                   class="font-semibold text-slate-900 hover:underline dark:text-slate-100">
                                    {{ $client->name }}
                                </a>
                            </td>

                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                <div>{{ $client->email ?? '—' }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $client->phone ?? '—' }}</div>
                            </td>

                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700
                                            dark:bg-slate-800 dark:text-slate-200">
                                    {{ $client->projects_count }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('clients.show', $client) }}"
                                       class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-800
                                              hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40">
                                        Ver
                                    </a>

                                    <button
                                        wire:click="edit({{ $client->id }})"
                                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-800
                                               hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40">
                                        Editar
                                    </button>

                                    <button
                                        onclick="confirm('¿Eliminar este cliente?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $client->id }})"
                                        class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-sm font-semibold text-rose-700
                                               hover:bg-rose-100 dark:border-rose-900/40 dark:bg-rose-900/20 dark:text-rose-200 dark:hover:bg-rose-900/30">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                No hay clientes todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3">
            {{ $clients->links() }}
        </div>
    </div>

    {{-- Modal (solo Livewire, sin Alpine) --}}
    @if($showForm)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            wire:keydown.escape.window="$set('showForm', false)"
        >
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/40"
                wire:click="$set('showForm', false)"
            ></div>

            {{-- Dialog --}}
            <div
                class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-5 shadow-xl
                    dark:border-slate-800 dark:bg-slate-900"
                wire:click.stop
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">
                            {{ $editingId ? 'Editar cliente' : 'Nuevo cliente' }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Datos básicos del cliente.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-950/50"
                        wire:click="$set('showForm', false)"
                    >✕</button>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Nombre *</label>
                        <input wire:model="name"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm
                                    dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                        @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Correo electrónico</label>
                            <input wire:model="email"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm
                                        dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                            @error('email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Teléfono</label>
                            <input wire:model="phone"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm
                                        dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" />
                            @error('phone') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Notas</label>
                        <textarea wire:model="notes" rows="3"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm
                                        dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></textarea>
                        @error('notes') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-end gap-2">
                    <button
                        type="button"
                        wire:click="$set('showForm', false)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800
                            hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40">
                        Cancelar
                    </button>

                    <button
                        type="button"
                        wire:click="save"
                        class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white
                            hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
