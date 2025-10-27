<?php

namespace App\Livewire;

use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Title('Códigos QR')]
class QrCodes extends Component
{
    use Toast;

    public $headers = [];

    public $qrCodes = [];

    // Variables para el modal de creación/edición
    public $showModal = false;

    public $editingQrCode = null;

    public $name = '';

    public $uri = '';

    public $is_active = true;

    public function mount()
    {
        $this->headers = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Nombre'],
            ['key' => 'uri', 'label' => 'URL'],
            ['key' => 'is_active', 'label' => 'Estado'],
        ];
        $this->loadQrCodes();
    }

    public function loadQrCodes()
    {
        $this->qrCodes = QrCode::all();
    }

    public function create()
    {
        $this->reset(['name', 'uri', 'is_active', 'editingQrCode']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function edit($qrCodeId)
    {
        $qrCode = QrCode::findOrFail($qrCodeId);
        $this->editingQrCode = $qrCode->id;
        $this->name = $qrCode->name;
        $this->uri = $qrCode->uri;
        $this->is_active = $qrCode->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'uri' => 'required|url|max:1000',
            'is_active' => 'boolean',
        ]);

        if ($this->editingQrCode) {
            $qrCode = QrCode::findOrFail($this->editingQrCode);
            $qrCode->update($validated);
            $this->success('Código QR actualizado exitosamente');
        } else {
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

    public function delete($qrCodeId)
    {
        QrCode::findOrFail($qrCodeId)->delete();
        $this->success('Código QR eliminado exitosamente');
        $this->loadQrCodes();
    }

    public function toggleStatus($qrCodeId)
    {
        // TODO: Implementar toggle de estado activo/inactivo
    }

    public function render()
    {
        return view('livewire.qr-codes');
    }
}
