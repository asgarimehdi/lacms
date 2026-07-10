<?php

use App\Models\Setting;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $site_name = '';
    public string $site_tagline = '';
    public string $contact_email = '';
    public bool $comments_enabled = true;

    public function mount(): void
    {
        $s = Setting::current();
        $this->site_name = $s->site_name;
        $this->site_tagline = $s->site_tagline ?? '';
        $this->contact_email = $s->contact_email ?? '';
        $this->comments_enabled = $s->comments_enabled;
    }

    protected function rules(): array
    {
        return [
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'comments_enabled' => 'boolean',
        ];
    }

    public function save(): void
    {
        Setting::current()->update($this->validate());
        $this->success('Settings saved.', position: 'toast-bottom');
    }
}; ?>

<div>
    <x-header title="Settings" separator progress-indicator />

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input label="Site Name" wire:model="site_name" />
            <x-input label="Site Tagline" wire:model="site_tagline" />
            <x-input label="Contact Email" wire:model="contact_email" type="email" icon="o-envelope" />
            <x-checkbox label="Enable Comments" wire:model="comments_enabled" class="mt-4" />
            <x-slot:actions>
                <x-button label="{{ __('cms.save') }}" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>