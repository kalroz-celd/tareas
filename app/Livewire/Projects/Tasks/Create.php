<?php

namespace App\Livewire\Projects\Tasks;

use App\Models\Project;
use App\Models\Task;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public Project $project;

    public string $title = '';
    public ?string $description = null;
    public string $status = 'todo';
    public string $priority = 'medium';
    public ?string $due_date = null;

    public function mount(Project $project): void
    {
        $this->project = $project;
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
        Task::create(array_merge($data, ['project_id' => $this->project->id]));

        session()->flash('toast', 'Tarea creada.');
        $this->redirectRoute('projects.tasks.index', $this->project, navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.projects.tasks.create');
    }
}
