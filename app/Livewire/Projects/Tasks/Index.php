<?php

namespace App\Livewire\Projects\Tasks;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public Project $project;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $priority = '';
    #[Url] public string $sortField = 'updated_at';
    #[Url] public string $sortDir   = 'desc';

    public int $perPage = 10;
    public ?array $selectedTask = null;

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function updatingPriority(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
            return;
        }
        $this->sortField = $field;
        $this->sortDir = 'asc';
    }

    public function delete(int $id): void
    {
        $this->project->tasks()->whereKey($id)->delete();
        session()->flash('toast', 'Tarea eliminada.');
        $this->resetPage();
    }

    public function openTaskSummary(int $taskId): void
    {
        $task = $this->project->tasks()->with('project:id,name')->findOrFail($taskId);

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
        $q = $this->project->tasks()->getQuery();

        if ($this->search !== '') {
            $q->where(function ($qq) {
                $qq->where('title', 'like', "%{$this->search}%")
                   ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->status !== '') {
            $q->where('status', $this->status);
        }

        if ($this->priority !== '') {
            $q->where('priority', $this->priority);
        }

        $tasks = $q->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.projects.tasks.index', compact('tasks'));
    }
}
