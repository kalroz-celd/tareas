<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Show extends Component
{
    public Client $client;

    // Modal pago
    public bool $showPaymentModal = false;
    public ?int $paymentProjectId = null;
    public ?string $payment_due_date = null;
    public ?string $amount = null;
    public ?string $currency = null;
    public ?string $payment_status = null;

    // Modal asociar proyecto
    public bool $showAttachModal = false;
    public ?int $attachProjectId = null;

    public function mount(Client $client): void
    {
        $this->client = $client;

        // defensivo: si wire:navigate restaura estado, esto lo apaga
        $this->showPaymentModal = false;
        $this->showAttachModal  = false;
    }

    /* ---------------------------
     * PAGO
     * --------------------------- */
    public function openPayment(int $projectId): void
    {
        $project = Project::query()
            ->where('client_id', $this->client->id)
            ->whereKey($projectId)
            ->firstOrFail();

        $this->paymentProjectId = $project->id;
        $this->payment_due_date = optional($project->payment_due_date)->format('Y-m-d');
        $this->amount = $project->amount !== null ? (string) $project->amount : null;
        $this->currency = $project->currency ?? 'CLP';
        $this->payment_status = $project->payment_status ?? 'pending';

        $this->showPaymentModal = true;
    }

    public function savePayment(): void
    {
        $this->validate([
            'paymentProjectId' => ['required', 'integer'],
            'payment_due_date' => ['nullable', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'payment_status' => ['nullable', 'in:pending,paid,overdue'],
        ]);

        $project = Project::query()
            ->where('client_id', $this->client->id)
            ->whereKey($this->paymentProjectId)
            ->firstOrFail();

        $project->update([
            'payment_due_date' => $this->payment_due_date,
            'amount' => $this->amount,
            'currency' => $this->currency ?: 'CLP',
            'payment_status' => $this->payment_status ?: 'pending',
            'paid_at' => ($this->payment_status === 'paid') ? now() : null,
        ]);

        $this->showPaymentModal = false;
        session()->flash('status_client', 'Pago actualizado.');
    }

    public function clearPayment(): void
    {
        $project = Project::query()
            ->where('client_id', $this->client->id)
            ->whereKey($this->paymentProjectId)
            ->firstOrFail();

        $project->update([
            'payment_due_date' => null,
            'amount' => null,
            'currency' => null,
            'payment_status' => null,
            'paid_at' => null,
        ]);

        $this->showPaymentModal = false;
        session()->flash('status_client', 'Pago limpiado (sin monto / sin vencimiento).');
    }

    /* ---------------------------
     * ASOCIAR PROYECTO
     * --------------------------- */
    public function openAttachModal(): void
    {
        $this->reset('attachProjectId');
        $this->showAttachModal = true;
    }

    public function attachProject(): void
    {
        $this->validate([
            'attachProjectId' => ['required', 'exists:projects,id'],
        ]);

        // Solo permitir asociar proyectos "propios" (sin cliente)
        $project = Project::query()
            ->whereNull('client_id')
            ->whereKey($this->attachProjectId)
            ->firstOrFail();

        $project->update(['client_id' => $this->client->id]);

        $this->showAttachModal = false;
        $this->reset('attachProjectId');

        session()->flash('status_client', 'Proyecto asociado al cliente.');
    }

    #[On('close-all-modals')]
    public function closeModals(): void
    {
        $this->showPaymentModal = false;
        $this->showAttachModal = false;
    }

    public function render()
    {
        $projects = $this->client->projects()
            ->orderBy('name')
            ->get();

        $availableProjects = Project::query()
            ->whereNull('client_id')
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('livewire.clients.show', compact('projects', 'availableProjects'));
    }
}
