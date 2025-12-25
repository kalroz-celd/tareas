<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $status = '';
    #[Url] public string $priority = '';
    #[Url] public string $archived = ''; // '' | '0' | '1'

    #[Url] public string $sortField = 'updated_at';
    #[Url] public string $sortDir   = 'desc';

    public int $perPage = 10;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }
    public function updatingPriority(): void { $this->resetPage(); }
    public function updatingArchived(): void { $this->resetPage(); }

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
        Project::query()->whereKey($id)->delete();
        session()->flash('toast', 'Proyecto eliminado.');
        $this->resetPage();
    }

    public function toggleArchive(int $id): void
    {
        $p = Project::query()->findOrFail($id);
        $p->update(['is_archived' => !$p->is_archived]);
        session()->flash('toast', $p->is_archived ? 'Proyecto archivado.' : 'Proyecto reactivado.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $q = Project::query();

        if ($this->search !== '') {
            $q->where(function ($qq) {
                $qq->where('name', 'like', "%{$this->search}%")
                   ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->status !== '') {
            $q->where('status', $this->status);
        }

        if ($this->priority !== '') {
            $q->where('priority', $this->priority);
        }

        if ($this->archived !== '') {
            $q->where('is_archived', (bool) ((int) $this->archived));
        }

        $projects = $q->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.projects.index', compact('projects')); // usa tu layout dashboard actual
    }
}
