<?php

namespace App\Livewire\Projects\Tasks;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public Project $project;
    public Task $task;

    public string $title = '';
    public ?string $description = null;
    public string $status = 'todo';
    public string $priority = 'medium';
    public ?string $due_date = null;

    public function mount(Project $project, Task $task): void
    {
        abort_unless($task->project_id === $project->id, 404);

        $this->project = $project;
        $this->task = $task;

        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;
        $this->priority = $task->priority;
        $this->due_date = optional($task->due_date)->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:180',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,doing,done,blocked',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();
        $this->task->update($data);

        session()->flash('toast', 'Tarea actualizada.');
        $this->redirectRoute('projects.tasks.index', $this->project, navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.projects.tasks.edit');
    }
}
