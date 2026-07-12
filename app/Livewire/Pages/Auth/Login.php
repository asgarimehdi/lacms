<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;

class Login extends Component
{
    use Toast;

    public string $email = '';
    public string $password = '';

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function mount(): void
    {
        if (auth()->user()) {
            $this->redirect('/admin');
        }
    }

    public function login(): void
    {
        $data = $this->validate();

        if (auth()->attempt($data)) {
            request()->session()->regenerate();
            $this->success('Welcome back!', position: 'toast-bottom');
            $this->redirect('/admin');
            return;
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('pages.auth.login');
    }
}