<?php

namespace App\Livewire\Notifications;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Header extends Component
{
    public array $taskNotifications = [];
    public array $paymentNotifications = [];

    public function mount(): void
    {
        $this->taskNotifications = $this->buildTaskNotifications();
        $this->paymentNotifications = $this->buildPaymentNotifications();
    }

    public static function hasNotifications(): bool
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();

        $taskCount = Task::query()
            ->whereNotNull('due_date')
            ->open()
            ->whereDate('due_date', '>=', $today)
            ->whereDate('due_date', '<=', $tomorrow)
            ->count();

        $paymentCount = Project::query()
            ->whereNotNull('client_id')
            ->whereNotNull('payment_due_date')
            ->whereDate('payment_due_date', '<=', $tomorrow)
            ->where(function ($query) {
                $query->whereNull('payment_status')
                    ->orWhere('payment_status', '!=', 'paid');
            })
            ->count();

        return ($taskCount + $paymentCount) > 0;
    }

    protected function buildTaskNotifications(): array
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();

        return Task::query()
            ->with('project:id,name')
            ->whereNotNull('due_date')
            ->whereIn('status', ['todo', 'doing', 'blocked'])
            ->whereBetween('due_date', [$today, $tomorrow])
            ->orderBy('due_date')
            ->limit(5)
            ->get()
            ->map(function (Task $task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'project' => $task->project?->name,
                    'due_date' => $task->due_date?->format('d/m/Y'),
                    'due_label' => $this->buildDueLabel($task->due_date),
                    'url' => $task->project_id
                        ? route('projects.tasks.index', ['project' => $task->project_id])
                        : route('tasks.index'),
                ];
            })
            ->toArray();
    }

    protected function buildPaymentNotifications(): array
    {
        $tomorrow = Carbon::today()->addDay();

        return Project::query()
            ->with('client:id,name')
            ->whereNotNull('client_id')
            ->whereNotNull('payment_due_date')
            ->whereDate('payment_due_date', '<=', $tomorrow)
            ->where(function ($query) {
                $query->whereNull('payment_status')
                    ->orWhere('payment_status', '!=', 'paid');
            })
            ->orderBy('payment_due_date')
            ->limit(5)
            ->get()
            ->map(function (Project $project) {
                $dueLabel = $this->buildDueLabel($project->payment_due_date);
                $statusLabel = $project->payment_status === 'overdue' ? 'Vencido' : 'Por vencer';

                if ($project->payment_due_date?->isToday()) {
                    $statusLabel = 'Vence hoy';
                }

                if ($project->payment_due_date?->isTomorrow()) {
                    $statusLabel = 'Vence mañana';
                }

                return [
                    'id' => $project->id,
                    'project' => $project->name,
                    'client' => $project->client?->name,
                    'due_date' => $project->payment_due_date?->format('d/m/Y'),
                    'due_label' => $dueLabel,
                    'status' => $statusLabel,
                    'amount' => $project->amount,
                    'currency' => $project->currency,
                    'url' => $project->client
                        ? route('clients.show', $project->client)
                        : route('clients.index'),
                ];
            })
            ->toArray();
    }

    protected function buildDueLabel(?Carbon $date): ?string
    {
        if (! $date) {
            return null;
        }

        if ($date->isToday()) {
            return 'Hoy';
        }

        if ($date->isTomorrow()) {
            return 'Mañana';
        }

        return $date->diffForHumans();
    }

    public function render()
    {
        return view('livewire.notifications.header');
    }
}
