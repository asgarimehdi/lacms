<?php

use App\Models\Tag;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Tag $tag = null;
    public string $name = '';

    protected function rules(): array
    {
        return ['name' => 'required|string|max:255'];
    }

    public function mount(?Tag $tag = null): void
    {
        if ($tag && $tag->exists) {
            $this->tag = $tag;
            $this->name = $tag->name;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->tag) {
            $this->tag->update($data);
        } else {
            Tag::create($data);
        }

        $this->success($this->tag ? __('cms.updated') : __('cms.created'), position: 'toast-bottom');
        $this->redirect('/admin/tags');
    }
}; ?>

<div>
    <x-header title="{{ $tag ? __('cms.edit') : __('cms.create') }} Tag" separator>
        <x-slot:actions>
            <x-button label="{{ __('cms.back') }}" link="/admin/tags" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input label="Tag Name" wire:model="name" />
            <x-slot:actions>
                <x-button label="{{ __('cms.save') }}" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>