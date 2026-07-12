<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ];
    }

    public function mount(): void
    {
        if (auth()->user()) {
            $this->redirect('/admin');
        }
    }

    public function register(): void
    {
        $data = $this->validate();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        auth()->login($user);
        request()->session()->regenerate();
        $this->success('Account created!', position: 'toast-bottom');
        $this->redirect('/admin');
    }

    public function render()
    {
        return view('pages.auth.register');
    }
}