<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    public array $stats = [];
    public $recentTasks;   // Collection
    public array $projects = [];
    public array $activity = [];
    public ?array $selectedTask = null;

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $now = now();
        $weekStart = $now->copy()->startOfWeek();

        // Stats
        $openTasks = Task::query()->whereIn('status', ['todo','doing','blocked'])->count();
        $openCreatedThisWeek = Task::query()
            ->whereIn('status', ['todo','doing','blocked'])
            ->where('created_at', '>=', $weekStart)
            ->count();

        $inProgress = Task::query()->where('status', 'doing')->count();
        $blocked    = Task::query()->where('status', 'blocked')->count();

        $doneTotal = Task::query()->where('status', 'done')->count();
        $doneLast30 = Task::query()
            ->where('status', 'done')
            ->where('updated_at', '>=', $now->copy()->subDays(30))
            ->count();

        $activeProjects = Project::query()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where('is_archived', false)
            ->count();

        $riskyProjects = Project::query()
            ->where('is_archived', false)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where(function ($q) use ($now) {
                $q->where('priority', 'urgent')
                  ->orWhere(function ($qq) use ($now) {
                      $qq->whereNotNull('due_date')
                         ->whereDate('due_date', '<=', $now->copy()->addDays(7));
                  });
            })
            ->count();

        $this->stats = [
            ['label' => 'Tareas abiertas', 'value' => $openTasks, 'hint' => '+' . $openCreatedThisWeek . ' esta semana'],
            ['label' => 'En progreso', 'value' => $inProgress, 'hint' => $blocked . ' bloqueadas'],
            ['label' => 'Completadas', 'value' => $doneTotal, 'hint' => $doneLast30 . ' últimos 30 días'],
            ['label' => 'Proyectos activos', 'value' => $activeProjects, 'hint' => $riskyProjects . ' con riesgo'],
        ];

        // Tareas recientes
        $this->recentTasks = Task::query()
            ->with('project:id,name')
            ->latest('updated_at')
            ->take(8)
            ->get();

        // Proyectos con progreso
        $projects = Project::query()
            ->where('is_archived', false)
            ->whereIn('status', ['active','planning','on_hold'])
            ->withCount(['tasks as tasks_total'])
            ->withCount(['tasks as tasks_done' => fn ($q) => $q->where('status', 'done')])
            ->latest('updated_at')
            ->take(6)
            ->get();

        $this->projects = $projects->map(function ($p) {
            $total = (int) $p->tasks_total;
            $done  = (int) $p->tasks_done;
            $pct   = $total > 0 ? (int) round(($done / $total) * 100) : 0;

            return [
                'id'     => $p->id,
                'name'   => $p->name,
                'status_label' => $p->status_label,
                'status_badge_class' => $p->status_badge_class,
                'status_badge_style' => $p->status_badge_style,
                'pct'    => $pct,
                'total'  => $total,
                'done'   => $done,
            ];
        })->all();

        // Actividad derivada
        $this->activity = $this->recentTasks->take(3)->map(function ($t) {
            $type = match ($t->status) {
                'done'    => 'done',
                'blocked' => 'blocked',
                default   => 'update',
            };

            return [
                'type' => $type,
                'title' => match ($type) {
                    'done'    => 'Tarea completada',
                    'blocked' => 'Bloqueo detectado',
                    default   => 'Actualización',
                },
                'text' => '“' . $t->title . '”' . ($t->project?->name ? ' · ' . $t->project->name : ''),
                'when' => $t->updated_at->diffForHumans(),
            ];
        })->all();
    }

    public function openTaskSummary(int $taskId): void
    {
        $task = Task::with('project:id,name')->findOrFail($taskId);

        $this->selectedTask = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status_label' => $task->status_label,
            'status_badge_class' => $task->status_badge_class,
            'priority_label' => $task->priority_label,
            'priority_badge_classes' => $task->priority_badge_classes,
            'due_date' => optional($task->due_date)->format('d/m/Y') ?? '—',
            'project_name' => $task->project?->name ?? '—',
            'project_id' => $task->project_id,
        ];

        $this->dispatch('open-modal', 'task-summary');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.dashboard');
    }
}
