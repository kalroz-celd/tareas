<div class="space-y-4">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                {{ $client->name }}
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                {{ $client->email ?? 'Sin email' }} · {{ $client->phone ?? 'Sin teléfono' }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                wire:click="openAttachModal"
                class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white
                    hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white"
            >
                + Asociar proyecto
            </button>

            <a href="{{ route('clients.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800
                    hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40">
                ← Volver
            </a>
        </div>
    </div>

    @if (session('status_client'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900
                    dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-100">
            {{ session('status_client') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
            <h2 class="text-sm font-extrabold text-slate-900 dark:text-slate-100">Proyectos del cliente</h2>
            <p class="text-xs text-slate-600 dark:text-slate-400">
                El monto y vencimiento se gestionan solo aquí (proyectos propios no necesariamente tienen pago).
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-950/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Proyecto
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Vencimiento
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Monto
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            Estado
                        </th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($projects as $project)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-950/40">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $project->name }}
                                </div>
                                @if(!empty($project->description))
                                    <div class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                        {{ $project->description }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                {{ $project->payment_due_date?->format('Y-m-d') ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-slate-700 dark:text-slate-300">
                                @if($project->amount !== null)
                                    {{ number_format((float)$project->amount, 0, ',', '.') }}
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $project->currency ?? 'CLP' }}</span>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700
                                            dark:bg-slate-800 dark:text-slate-200">
                                    {{ $project->payment_status ?? '—' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <button
                                    wire:click="openPayment({{ $project->id }})"
                                    class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-800
                                           hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40">
                                    Pago
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                Este cliente aún no tiene proyectos asociados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal pago (solo Livewire) --}}
    @if($showPaymentModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            wire:keydown.escape.window="$set('showPaymentModal', false)"
        >
            <div
                class="absolute inset-0 bg-black/40"
                wire:click="$set('showPaymentModal', false)"
            ></div>

            <div
                class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-xl dark:border-slate-800 dark:bg-slate-900"
                wire:click.stop
            >
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Actualizar pago</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Edita fecha de vencimiento, monto y estado del pago del proyecto.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-950/50"
                        wire:click="$set('showPaymentModal', false)"
                    >✕</button>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Vencimiento</label>
                        <input
                            type="date"
                            wire:model.defer="payment_due_date"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        />
                        @error('payment_due_date') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Monto</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                wire:model.defer="amount"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                            />
                            @error('amount') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Moneda</label>
                            <input
                                type="text"
                                maxlength="3"
                                wire:model.defer="currency"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm uppercase tracking-wide dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                            />
                            @error('currency') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Estado del pago</label>
                        <select
                            wire:model.defer="payment_status"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        >
                            <option value="pending">Pendiente</option>
                            <option value="paid">Pagado</option>
                            <option value="overdue">Atrasado</option>
                        </select>
                        @error('payment_status') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap justify-end gap-2">
                    <button
                        type="button"
                        wire:click="clearPayment"
                        class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100 dark:border-amber-900/40 dark:bg-amber-900/30 dark:text-amber-50"
                    >
                        Limpiar pago
                    </button>

                    <button
                        type="button"
                        wire:click="$set('showPaymentModal', false)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        wire:click="savePayment"
                        class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white"
                    >
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal asociar proyecto (solo Livewire) --}}
    @if($showAttachModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center"
            wire:keydown.escape.window="$set('showAttachModal', false)"
        >
            <div
                class="absolute inset-0 bg-black/40"
                wire:click="$set('showAttachModal', false)"
            ></div>

            <div
                class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-xl
                    dark:border-slate-800 dark:bg-slate-900"
                wire:click.stop
            >
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Asociar proyecto</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Solo aparecen proyectos sin cliente.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-950/50"
                        wire:click="$set('showAttachModal', false)"
                    >✕</button>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Proyecto</label>
                        <select
                            wire:model="attachProjectId"
                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm
                                dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                        >
                            <option value="">— Seleccionar —</option>
                            @foreach($availableProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('attachProjectId') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror

                        @if($availableProjects->isEmpty())
                            <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                No hay proyectos disponibles para asociar (todos ya tienen cliente o no existen).
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="$set('showAttachModal', false)"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800
                            hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-950/40"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        wire:click="attachProject"
                        class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white
                            hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white"
                    >
                        Asociar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
