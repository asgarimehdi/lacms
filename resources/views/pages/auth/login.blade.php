<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component {
    use Toast, WithPagination;

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
}; ?>

<div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="md:w-96 w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-primary">LACMS</h1>
                <p class="text-base-content/60 mt-1">{{ __('cms.sign_in_to_continue') }}</p>
            </div>
            <x-card shadow>
                <x-form wire:submit="login">
                    <x-input label="E-mail" wire:model="email" icon="o-envelope" />
                    <x-input label="Password" wire:model="password" type="password" icon="o-key" />
                    <x-slot:actions>
                        <x-button label="Create an account" class="btn-ghost" link="/register" />
                        <x-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />
                    </x-slot:actions>
                </x-form>
            </x-card>
        </div>
    </div>
</div>