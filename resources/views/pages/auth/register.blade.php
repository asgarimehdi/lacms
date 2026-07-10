<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
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
}; ?>

<div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="md:w-96 w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-primary">LACMS</h1>
                <p class="text-base-content/60 mt-1">{{ __('cms.create_your_account') }}</p>
            </div>
            <x-card shadow>
                <x-form wire:submit="register">
                    <x-input label="Name" wire:model="name" icon="o-user" />
                    <x-input label="E-mail" wire:model="email" icon="o-envelope" />
                    <x-input label="Password" wire:model="password" type="password" icon="o-key" />
                    <x-input label="Confirm Password" wire:model="password_confirmation" type="password" icon="o-key" />
                    <x-slot:actions>
                        <x-button label="Already registered?" class="btn-ghost" link="/login" />
                        <x-button label="Register" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="register" />
                    </x-slot:actions>
                </x-form>
            </x-card>
        </div>
    </div>
</div>