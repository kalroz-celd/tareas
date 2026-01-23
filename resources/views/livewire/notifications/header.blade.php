@if (count($taskNotifications) || count($paymentNotifications))
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    Notificaciones
                </div>
                <div class="text-sm text-slate-600 dark:text-slate-300">
                    Recordatorios próximos para tareas y pagos.
                </div>
            </div>
            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200">
                {{ count($taskNotifications) + count($paymentNotifications) }} alerta(s)
            </span>
        </div>

        <div class="mt-4 grid gap-3 lg:grid-cols-2">
            @if (count($taskNotifications))
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/40">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Tareas por vencer
                    </div>
                    <ul class="mt-2 space-y-2 text-sm">
                        @foreach ($taskNotifications as $task)
                            <li class="flex items-start justify-between gap-3">
                                <div>
                                    <a class="font-semibold text-slate-900 hover:underline dark:text-white" href="{{ $task['url'] }}">
                                        {{ $task['title'] }}
                                    </a>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $task['project'] ?? 'Sin proyecto' }}
                                        @if ($task['due_date'])
                                            · vence {{ $task['due_date'] }}
                                        @endif
                                    </div>
                                </div>
                                @if ($task['due_label'])
                                    <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                                        {{ $task['due_label'] }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (count($paymentNotifications))
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/40">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        Pagos de clientes
                    </div>
                    <ul class="mt-2 space-y-2 text-sm">
                        @foreach ($paymentNotifications as $payment)
                            <li class="flex items-start justify-between gap-3">
                                <div>
                                    <a class="font-semibold text-slate-900 hover:underline dark:text-white" href="{{ $payment['url'] }}">
                                        {{ $payment['project'] }}
                                    </a>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $payment['client'] ?? 'Sin cliente' }}
                                        @if ($payment['due_date'])
                                            · vence {{ $payment['due_date'] }}
                                        @endif
                                        @if ($payment['amount'])
                                            · {{ number_format((float) $payment['amount'], 2, ',', '.') }} {{ $payment['currency'] ?? '' }}
                                        @endif
                                    </div>
                                </div>
                                <span class="shrink-0 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">
                                    {{ $payment['status'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endif
