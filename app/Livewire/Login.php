<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Login extends Component
{
    #[Layout('components.layouts.guest')]
    public function render()
    {
        return view('livewire.login');
    }
}
