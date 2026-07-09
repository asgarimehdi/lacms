<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Page;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateModal = false;
    public $showEditModal = false;

    public $pageId;
    public $title;
    public $slug;
    public $content;
    public $status = 'draft';
    public $parent_id = 0;
    public $sort = 0;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => $this->pageId
                ? 'required|string|max:255|unique:pages,slug,' . $this->pageId
                : 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'parent_id' => 'nullable|integer',
            'sort' => 'integer|min:0',
        ];
    }

    protected function messages(): array
    {
        return [
            'slug.unique' => 'این slug قبلاً استفاده شده است.',
        ];
    }

    public function render()
    {
        $pages = Page::when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->whereNull('parent_id')
            ->orderBy('sort')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.page-manager', compact('pages'));
    }

    public function resetForm()
    {
        $this->pageId = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->status = 'draft';
        $this->parent_id = 0;
        $this->sort = 0;
        $this->resetErrorBag();
    }

    public function createPage() { $this->resetForm(); $this->showCreateModal = true; }

    public function storePage()
    {
        $this->validate();
        Page::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'parent_id' => $this->parent_id ?: null,
            'sort' => $this->sort,
        ]);
        $this->showCreateModal = false;
        $this->resetForm();
        $this->dispatch('notify', 'صفحه با موفقیت ایجاد شد.');
    }

    public function editPage(Page $page)
    {
        $this->pageId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->status = $page->status;
        $this->parent_id = $page->parent_id ?? 0;
        $this->sort = $page->sort;
        $this->showEditModal = true;
    }

    public function updatePage()
    {
        $this->validate();
        Page::find($this->pageId)->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'parent_id' => $this->parent_id ?: null,
            'sort' => $this->sort,
        ]);
        $this->showEditModal = false;
        $this->resetForm();
        $this->dispatch('notify', 'صفحه با موفقیت به‌روزرسانی شد.');
    }

    public function deletePage(Page $page)
    {
        $page->delete();
        $this->dispatch('notify', 'صفحه با موفقیت حذف شد.');
    }
}
?>

<div>
    <x-mary-header title="مدیریت صفحات" subtitle="ایجاد، ویرایش و مدیریت صفحات سایت">
        <x-slot:actions>
            <x-mary-button label="صفحه جدید" icon="o-plus" class="btn-primary" wire:click="createPage" />
        </x-slot:actions>
    </x-mary-header>

    <x-mary-card>
        <div class="flex gap-4 mb-4">
            <x-mary-input label="جستجو" placeholder="جستجو..." wire:model.live.debounce="search" icon="o-magnifying-glass" clearable class="flex-1" />
        </div>

        <x-mary-table :data="$pages" per-page="10" striped hoverable>
            <@column(label="عنوان" align="right" key="title">
                <div class="font-bold">{{ $row->title }}</div>
                <div class="text-xs text-gray-500">{{ $row->slug }}</div>
            </@column>
            <@column(label="وضعیت" align="center">
                @switch($row->status)
                    @case('published') <x-mary-badge label="منتشر شده" class="badge-success" /> @break
                    @case('archived') <x-mary-badge label="آرشیو" class="badge-warning" /> @break
                    @default <x-mary-badge label="پیش‌نویس" class="badge-ghost" />
                @endswitch
            </@column>
            <@column(label="ترتیب" align="center">{{ $row->sort }}</@column>
            <@column(label="عملیات" align="center">
                <x-mary-button icon="o-pencil-square" wire:click="editPage({{ $row->id }})" class="btn-ghost btn-sm" />
                <x-mary-button icon="o-trash" wire:click="deletePage({{ $row->id }})" class="btn-ghost btn-sm text-error" wire:confirm="آیا از حذف این صفحه اطمینان دارید؟" />
            </@column>
        </x-mary-table>
    </x-mary-card>

    {{-- Create Modal --}}
    <x-mary-modal wire:model="showCreateModal" title="ایجاد صفحه جدید" size="xl" separator>
        <div class="grid grid-cols-2 gap-4">
            <x-mary-input label="عنوان" wire:model="title" />
            <x-mary-input label="Slug" wire:model="slug" dir="ltr" />
        </div>
        <div class="grid grid-cols-3 gap-4 mt-4">
            <x-mary-select label="وضعیت" wire:model="status" :options="[['id' => 'draft', 'name' => 'پیش‌نویس'], ['id' => 'published', 'name' => 'منتشر شده'], ['id' => 'archived', 'name' => 'آرشیو']]" />
            <x-mary-select label="صفحه والد" wire:model="parent_id" :options="App\Models\Page::whereNull('parent_id')->pluck('title', 'id')->prepend('بدون والد', 0)->toArray()" />
            <x-mary-input label="ترتیب" wire:model="sort" type="number" />
        </div>
        <x-mary-textarea label="محتوا" wire:model="content" rows="8" class="mt-4" />
        <x-slot:actions>
            <x-mary-button label="انصراف" wire:click="showCreateModal = false" />
            <x-mary-button label="ذخیره" icon="o-check" class="btn-primary" wire:click="storePage" />
        </x-slot:actions>
    </x-mary-modal>

    {{-- Edit Modal --}}
    <x-mary-modal wire:model="showEditModal" title="ویرایش صفحه" size="xl" separator>
        <input type="hidden" wire:model="pageId">
        <div class="grid grid-cols-2 gap-4">
            <x-mary-input label="عنوان" wire:model="title" />
            <x-mary-input label="Slug" wire:model="slug" dir="ltr" />
        </div>
        <div class="grid grid-cols-3 gap-4 mt-4">
            <x-mary-select label="وضعیت" wire:model="status" :options="[['id' => 'draft', 'name' => 'پیش‌نویس'], ['id' => 'published', 'name' => 'منتشر شده'], ['id' => 'archived', 'name' => 'آرشیو']]" />
            <x-mary-select label="صفحه والد" wire:model="parent_id" :options="App\Models\Page::whereNull('parent_id')->where('id', '!=', $pageId)->pluck('title', 'id')->prepend('بدون والد', 0)->toArray()" />
            <x-mary-input label="ترتیب" wire:model="sort" type="number" />
        </div>
        <x-mary-textarea label="محتوا" wire:model="content" rows="8" class="mt-4" />
        <x-slot:actions>
            <x-mary-button label="انصراف" wire:click="showEditModal = false" />
            <x-mary-button label="بروزرسانی" icon="o-check" class="btn-primary" wire:click="updatePage" />
        </x-slot:actions>
    </x-mary-modal>

    <x-mary-toast />
</div>