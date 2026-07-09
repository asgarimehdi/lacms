<?php

namespace App\Http\Livewire;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class PageManager extends Component
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

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:pages,slug',
        'content' => 'nullable|string',
        'status' => 'required|in:draft,published,archived',
        'parent_id' => 'nullable|integer|exists:pages,id',
        'sort' => 'integer|min:0',
    ];

    protected $messages = [
        'slug.unique' => 'اینslug قبلاً استفاده شده است.',
    ];

    public function render()
    {
        $pages = Page::when($this->search, function ($query) {
            $query->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('slug', 'like', '%'.$this->search.'%');
        })
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
        $this->resetValidation();
    }

    public function createPage()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function storePage()
    {
        $this->validate();

        Page::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'parent_id' => $this->parent_id === '' ? null : $this->parent_id,
            'sort' => $this->sort,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        $this->dispatch('pageCreated');
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
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,'.$this->pageId,
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'parent_id' => 'nullable|integer|exists:pages,id',
            'sort' => 'integer|min:0',
        ]);

        $page = Page::find($this->pageId);
        $page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'parent_id' => $this->parent_id === '' ? null : $this->parent_id,
            'sort' => $this->sort,
        ]);

        $this->showEditModal = false;
        $this->resetForm();
        $this->dispatch('pageUpdated');
        $this->dispatch('notify', 'صفحه با موفقیت به‌روزرسانی شد.');
    }

    public function deletePage(Page $page)
    {
        $page->delete();
        $this->dispatch('pageDeleted');
        $this->dispatch('notify', 'صفحه با موفقیت حذف شد.');
    }
}
