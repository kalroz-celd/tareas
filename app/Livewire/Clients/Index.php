<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $notes = null;

    public function mount(): void
    {
        $this->showForm = false;
    }

    protected function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $client = Client::findOrFail($id);

        $this->editingId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->notes = $client->notes;

        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        Client::updateOrCreate(
            ['id' => $this->editingId],
            $data
        );

        $this->showForm = false;
        $this->resetForm();

        session()->flash('status', $this->editingId ? 'Cliente actualizado.' : 'Cliente creado.');
    }

    public function delete(int $id): void
    {
        Client::query()->whereKey($id)->delete();
        session()->flash('status', 'Cliente eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'notes']);
    }

    public function render()
    {
        $clients = Client::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->withCount('projects')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.clients.index', compact('clients'));
    }
}
