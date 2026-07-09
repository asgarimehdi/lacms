<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ];

    protected $messages = [
        'email.required' => 'ایمیل الزامی است.',
        'email.email' => 'فرمت ایمیل نامعتبر است.',
        'password.required' => 'رمز عبور الزامی است.',
        'password.min' => 'رمز عبور باید حداقل ۶ کاراکتر باشد.',
    ];

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            request()->session()->regenerate();
            return redirect()->intended('/admin/pages');
        }

        $this->addError('email', 'ایمیل یا رمز عبور اشتباه است.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}