<?php

namespace App\Livewire\Passwords;

use App\Models\PasswordEntry;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url] public string $search = '';
    #[Url] public string $filterCategory = '';
    #[Url] public string $filterProject = '';

    #[Url] public string $sortField = 'updated_at';
    #[Url] public string $sortDir   = 'desc';

    public int $perPage = 12;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $title = '';
    public string $entryCategory = 'personal';
    public ?int $projectId = null;
    public string $username = '';
    public string $secret = '';
    public string $url = '';
    public string $notes = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCategory(): void { $this->resetPage(); }
    public function updatingFilterProject(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
            return;
        }
        $this->sortField = $field;
        $this->sortDir = 'asc';
    }

    public function edit(int $id): void
    {
        $entry = PasswordEntry::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $this->editingId = $entry->id;
        $this->title = $entry->title;
        $this->entryCategory = $entry->category;
        $this->projectId = $entry->project_id;
        $this->username = $entry->username ?? '';
        $this->secret = $entry->secret;
        $this->url = $entry->url ?? '';
        $this->notes = $entry->notes ?? '';
        $this->showModal = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'entryCategory' => ['required', Rule::in(['project', 'personal'])],
            'projectId' => ['nullable', 'integer', 'exists:projects,id', Rule::requiredIf($this->entryCategory === 'project')],
            'username' => ['nullable', 'string', 'max:255'],
            'secret' => ['required', 'string', 'max:2000'],
            'url' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ];

        $this->validate($rules);

        $payload = [
            'user_id' => Auth::id(),
            'project_id' => $this->entryCategory === 'project' ? $this->projectId : null,
            'category' => $this->entryCategory,
            'title' => $this->title,
            'username' => $this->username === '' ? null : $this->username,
            'secret' => $this->secret,
            'url' => $this->url === '' ? null : $this->url,
            'notes' => $this->notes === '' ? null : $this->notes,
        ];

        if ($this->editingId) {
            PasswordEntry::query()
                ->where('user_id', Auth::id())
                ->whereKey($this->editingId)
                ->update($payload);
            session()->flash('toast', 'Credencial actualizada.');
        } else {
            PasswordEntry::query()->create($payload);
            session()->flash('toast', 'Credencial guardada.');
        }

        $this->resetForm();
        $this->showModal = false;
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->entryCategory = 'personal';
        $this->projectId = null;
        $this->username = '';
        $this->secret = '';
        $this->url = '';
        $this->notes = '';
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function delete(int $id): void
    {
        PasswordEntry::query()
            ->where('user_id', Auth::id())
            ->whereKey($id)
            ->delete();

        session()->flash('toast', 'Credencial eliminada.');
        $this->resetPage();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = PasswordEntry::query()
            ->with('project:id,name')
            ->where('user_id', Auth::id());

        if ($this->search !== '') {
            $query->where(function ($qq) {
                $qq->where('title', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('url', 'like', "%{$this->search}%")
                    ->orWhere('notes', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterCategory !== '') {
            $query->where('category', $this->filterCategory);
        }

        if ($this->filterProject !== '') {
            $query->where('project_id', $this->filterProject);
        }

        $entries = $query->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        $projects = Project::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.passwords.index', compact('entries', 'projects'));
    }
}
