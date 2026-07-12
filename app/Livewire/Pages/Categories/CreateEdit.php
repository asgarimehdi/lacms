<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateEdit extends Component
{
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

    public function render()
    {
        return view('pages.categories.create-edit');
    }
}