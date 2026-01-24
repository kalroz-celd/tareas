diff --git a/resources/views/livewire/notifications/header.blade.php b/resources/views/livewire/notifications/header.blade.php
index b42b54365618ed166e5f0863abcaee39e94e217d..c02e497bb53ed0597bb3c8950e159c815c89766f 100644
--- a/resources/views/livewire/notifications/header.blade.php
+++ b/resources/views/livewire/notifications/header.blade.php
@@ -1,80 +1,133 @@
 @if (count($taskNotifications) || count($paymentNotifications))
-    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900 transition-colors duration-300">
-        <div class="flex flex-wrap items-center justify-between gap-3">
-            <div>
-                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
-                    Notificaciones
-                </div>
-                <div class="text-sm text-slate-600 dark:text-slate-300">
-                    Recordatorios próximos para tareas y pagos.
-                </div>
-            </div>
-            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200">
-                {{ count($taskNotifications) + count($paymentNotifications) }} alerta(s)
-            </span>
+    @php($totalNotifications = count($taskNotifications) + count($paymentNotifications))
+    <div x-data="{ open: false }" @keydown.escape.window="open = false">
+        <div class="flex justify-end">
+            <button
+                class="relative inline-flex items-center justify-center rounded-full border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
+                @click="open = !open"
+                :aria-expanded="open.toString()"
+                aria-controls="notifications-panel"
+                aria-label="Ver notificaciones"
+            >
+                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
+                    <path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
+                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
+                </svg>
+                <span class="absolute -right-1.5 -top-1.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-xs font-semibold text-white">
+                    {{ $totalNotifications }}
+                </span>
+            </button>
         </div>
 
-        <div class="mt-4 grid gap-3 lg:grid-cols-2">
-            @if (count($taskNotifications))
-                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/40">
-                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
-                        Tareas por vencer
+        <div
+            x-cloak
+            x-show="open"
+            x-transition.opacity
+            class="fixed inset-0 z-40 bg-slate-900/40"
+            @click="open = false"
+        ></div>
+
+        <aside
+            id="notifications-panel"
+            x-cloak
+            x-show="open"
+            x-transition:enter="transition ease-out duration-200 transform"
+            x-transition:enter-start="translate-x-full"
+            x-transition:enter-end="translate-x-0"
+            x-transition:leave="transition ease-in duration-150 transform"
+            x-transition:leave-start="translate-x-0"
+            x-transition:leave-end="translate-x-full"
+            class="fixed right-0 top-0 z-50 h-full w-full max-w-md border-l border-slate-200 bg-white shadow-2xl dark:border-slate-800 dark:bg-slate-950"
+        >
+            <div class="flex h-full flex-col">
+                <div class="flex items-start justify-between border-b border-slate-200 px-6 py-5 dark:border-slate-800">
+                    <div>
+                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
+                            Notificaciones
+                        </div>
+                        <div class="text-sm text-slate-600 dark:text-slate-300">
+                            Recordatorios próximos para tareas y pagos.
+                        </div>
+                    </div>
+                    <div class="flex items-center gap-2">
+                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-200">
+                            {{ $totalNotifications }} alerta(s)
+                        </span>
+                        <button
+                            class="rounded-full border border-slate-200 p-2 text-slate-500 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
+                            @click="open = false"
+                            aria-label="Cerrar notificaciones"
+                        >
+                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
+                                <path d="M18 6 6 18M6 6l12 12"/>
+                            </svg>
+                        </button>
                     </div>
-                    <ul class="mt-2 space-y-2 text-sm">
-                        @foreach ($taskNotifications as $task)
-                            <li class="flex items-start justify-between gap-3">
-                                <div>
-                                    <a class="font-semibold text-slate-900 hover:underline dark:text-white" href="{{ $task['url'] }}">
-                                        {{ $task['title'] }}
-                                    </a>
-                                    <div class="text-xs text-slate-500 dark:text-slate-400">
-                                        {{ $task['project'] ?? 'Sin proyecto' }}
-                                        @if ($task['due_date'])
-                                            · vence {{ $task['due_date'] }}
-                                        @endif
-                                    </div>
-                                </div>
-                                @if ($task['due_label'])
-                                    <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
-                                        {{ $task['due_label'] }}
-                                    </span>
-                                @endif
-                            </li>
-                        @endforeach
-                    </ul>
                 </div>
-            @endif
 
-            @if (count($paymentNotifications))
-                <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/40">
-                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
-                        Pagos de clientes
-                    </div>
-                    <ul class="mt-2 space-y-2 text-sm">
-                        @foreach ($paymentNotifications as $payment)
-                            <li class="flex items-start justify-between gap-3">
-                                <div>
-                                    <a class="font-semibold text-slate-900 hover:underline dark:text-white" href="{{ $payment['url'] }}">
-                                        {{ $payment['project'] }}
-                                    </a>
-                                    <div class="text-xs text-slate-500 dark:text-slate-400">
-                                        {{ $payment['client'] ?? 'Sin cliente' }}
-                                        @if ($payment['due_date'])
-                                            · vence {{ $payment['due_date'] }}
-                                        @endif
-                                        @if ($payment['amount'])
-                                            · {{ number_format((float) $payment['amount'], 2, ',', '.') }} {{ $payment['currency'] ?? '' }}
+                <div class="flex-1 space-y-4 overflow-y-auto px-6 py-5">
+                    @if (count($taskNotifications))
+                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/40">
+                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
+                                Tareas por vencer
+                            </div>
+                            <ul class="mt-2 space-y-2 text-sm">
+                                @foreach ($taskNotifications as $task)
+                                    <li class="flex items-start justify-between gap-3">
+                                        <div>
+                                            <a class="font-semibold text-slate-900 hover:underline dark:text-white" href="{{ $task['url'] }}">
+                                                {{ $task['title'] }}
+                                            </a>
+                                            <div class="text-xs text-slate-500 dark:text-slate-400">
+                                                {{ $task['project'] ?? 'Sin proyecto' }}
+                                                @if ($task['due_date'])
+                                                    · vence {{ $task['due_date'] }}
+                                                @endif
+                                            </div>
+                                        </div>
+                                        @if ($task['due_label'])
+                                            <span class="shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
+                                                {{ $task['due_label'] }}
+                                            </span>
                                         @endif
-                                    </div>
-                                </div>
-                                <span class="shrink-0 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">
-                                    {{ $payment['status'] }}
-                                </span>
-                            </li>
-                        @endforeach
-                    </ul>
+                                    </li>
+                                @endforeach
+                            </ul>
+                        </div>
+                    @endif
+
+                    @if (count($paymentNotifications))
+                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950/40">
+                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
+                                Pagos de clientes
+                            </div>
+                            <ul class="mt-2 space-y-2 text-sm">
+                                @foreach ($paymentNotifications as $payment)
+                                    <li class="flex items-start justify-between gap-3">
+                                        <div>
+                                            <a class="font-semibold text-slate-900 hover:underline dark:text-white" href="{{ $payment['url'] }}">
+                                                {{ $payment['project'] }}
+                                            </a>
+                                            <div class="text-xs text-slate-500 dark:text-slate-400">
+                                                {{ $payment['client'] ?? 'Sin cliente' }}
+                                                @if ($payment['due_date'])
+                                                    · vence {{ $payment['due_date'] }}
+                                                @endif
+                                                @if ($payment['amount'])
+                                                    · {{ number_format((float) $payment['amount'], 2, ',', '.') }} {{ $payment['currency'] ?? '' }}
+                                                @endif
+                                            </div>
+                                        </div>
+                                        <span class="shrink-0 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">
+                                            {{ $payment['status'] }}
+                                        </span>
+                                    </li>
+                                @endforeach
+                            </ul>
+                        </div>
+                    @endif
                 </div>
-            @endif
-        </div>
+            </div>
+        </aside>
     </div>
 @endif
