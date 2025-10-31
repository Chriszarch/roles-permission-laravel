<?php

namespace App\Livewire;

use App\Models\QrCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Title('Códigos QR')]
class QrCodes extends Component
{
    use AuthorizesRequests;
    use Toast;

    public $qrCodes = [];

    // Variables para el modal de creación/edición
    public $showModal = false;

    public $editingQrCode = null;

    public $name = '';

    public $uri = '';

    public $is_active = true;

    // Buscador
    public $search = '';

    // Filtros
    public $statusFilter = 'all';

    public $perPage = 10;

    public function mount(): void
    {
        $this->loadQrCodes();
    }

    public function loadQrCodes(): void
    {
        $query = QrCode::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('uri', 'like', '%'.$this->search.'%')
                    ->orWhere('uuid', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $this->qrCodes = $query->latest()->limit($this->perPage)->get();
    }

    public function updatedSearch(): void
    {
        $this->loadQrCodes();
    }

    public function updatedStatusFilter(): void
    {
        $this->loadQrCodes();
    }

    public function updatedPerPage(): void
    {
        $this->loadQrCodes();
    }

    public function create(): void
    {
        Gate::authorize('create', QrCode::class);

        $this->reset(['name', 'uri', 'is_active', 'editingQrCode']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($qrCodeId): void
    {
        $qrCode = QrCode::findOrFail($qrCodeId);
        Gate::authorize('update', $qrCode);

        $this->editingQrCode = $qrCode->id;
        $this->name = $qrCode->name;
        $this->uri = $qrCode->uri;
        $this->is_active = $qrCode->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'uri' => 'required|url|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($this->editingQrCode) {
            $qrCode = QrCode::findOrFail($this->editingQrCode);
            Gate::authorize('update', $qrCode);

            $qrCode->update($validated);
            $this->success('Código QR actualizado exitosamente');
        } else {
            Gate::authorize('create', QrCode::class);

            QrCode::create([
                ...$validated,
                'user_id' => Auth::id(),
            ]);
            $this->success('Código QR creado exitosamente');
        }

        $this->showModal = false;
        $this->reset(['name', 'uri', 'is_active', 'editingQrCode']);
        $this->loadQrCodes();
    }

    public function delete($qrCodeId): void
    {
        $qrCode = QrCode::findOrFail($qrCodeId);
        Gate::authorize('delete', $qrCode);

        $qrCode->delete();
        $this->success('Código QR eliminado exitosamente');
        $this->loadQrCodes();
    }

    public function toggleStatus($qrCodeId): void
    {
        $qrCode = QrCode::findOrFail($qrCodeId);
        Gate::authorize('update', $qrCode);

        $qrCode->update(['is_active' => ! $qrCode->is_active]);
        $this->success($qrCode->is_active ? 'Código QR activado' : 'Código QR desactivado');
        $this->loadQrCodes();
    }

    public function render()
    {
        return view('livewire.qr-codes');
    }
}
