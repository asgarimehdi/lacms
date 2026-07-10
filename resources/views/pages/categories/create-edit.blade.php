<?php

use App\Models\Category;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Category $category = null;
    public string $name = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function mount(?Category $category = null): void
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->name = $category->name;
        }
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
        $this->redirect('/admin/categories');
    }
}; ?>

<div>
    <x-header :title="$category ? __('cms.edit') : __('cms.create')" separator>
        <x-slot:actions>
            <x-button :label="__('cms.back')" link="{{ route('admin.categories.index') }}" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input :label="__('cms.category_name')" wire:model="name" />
            <x-slot:actions>
                <x-button :label="__('cms.save')" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>