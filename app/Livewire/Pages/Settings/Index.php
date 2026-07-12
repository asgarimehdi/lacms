<?php

namespace App\Livewire\Pages\Settings;

use App\Models\Setting;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
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

    public function render()
    {
        return view('pages.settings.index');
    }
}