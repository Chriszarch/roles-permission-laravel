<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Only allow login for active accounts
        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
            'is_active' => 1,
        ], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'Las credenciales no coinciden con nuestros registros.');
    }

    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.login');
    }
}
