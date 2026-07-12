<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function mount(): void
    {
        if (Auth::user()) {
            redirect('/admin');
        }
    }

    public function login(): void
    {
        $data = $this->validate();

        if (Auth::attempt($data, $this->remember)) {
            request()->session()->regenerate();
            redirect('/admin');
            return;
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}