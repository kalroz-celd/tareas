<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Form extends Component
{
    public ?Project $project = null;

    public string $name = '';
    public ?string $description = null;
    public string $status = 'planning';
    public string $priority = 'medium';
    public ?string $start_date = null;
    public ?string $due_date = null;
    public bool $is_archived = false;

    public function mount(?Project $project = null): void
    {
        $this->project = $project;

        if ($project) {
            $this->name        = $project->name;
            $this->description = $project->description;
            $this->status      = $project->status;
            $this->priority    = $project->priority;
            $this->start_date  = optional($project->start_date)->format('Y-m-d');
            $this->due_date    = optional($project->due_date)->format('Y-m-d');
            $this->is_archived = (bool) $project->is_archived;
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['planning','active','on_hold','completed','cancelled'])],
            'priority' => ['required', Rule::in(['low','medium','high','urgent'])],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_archived' => ['boolean'],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->project) {
            $this->project->update($data);
            session()->flash('toast', 'Proyecto actualizado.');
        } else {
            Project::create($data);
            session()->flash('toast', 'Proyecto creado.');
        }

        return redirect()->route('projects.index');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.projects.form');
    }
}
