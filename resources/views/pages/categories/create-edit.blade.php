<?php

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Category $category = null;
    public string $name = '';

    public function mount(?Category $category = null): void
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->name = $category->name;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->category) {
            $this->category->update($data);
        } else {
            Category::create($data);
        }

        $this->success($this->category ? __('cms.updated') : __('cms.created'), position: 'toast-bottom');
        $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.categories-form');
    }
}; ?>

<div>
    <x-header :title="$category ? __('cms.edit') : __('cms.create')" separator>
        <x-slot:actions>
            <x-button :label="__('cms.back')" link="{{ route('categories.index') }}" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input :label="__('cms.title')" wire:model="name" />
            <x-slot:actions>
                <x-button :label="__('cms.save')" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
