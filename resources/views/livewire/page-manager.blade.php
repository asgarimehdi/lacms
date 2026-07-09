<div>
    <x-mary-header title="مدیریت صفحات" subtitle="ایجاد، ویرایش و مدیریت صفحات سایت">
        <x-slot:actions>
            <x-mary-button label="صفحه جدید" icon="o-plus" class="btn-primary" wire:click="createPage" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Search & Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-mary-input 
            label="جستجو" 
            placeholder="جستجو در عنوان یا slug..." 
            wire:model.live.debounce="search"
            icon="o-magnifying-glass"
            clearable
        />
        <x-mary-stat title="کل صفحات" :value="App\Models\Page::count()" icon="o-document-text" color="text-info" />
        <x-mary-stat title="منتشر شده" :value="App\Models\Page::where('status', 'published')->count()" icon="o-check-circle" color="text-success" />
    </div>

    {{-- Table --}}
    <x-mary-card>
        <x-mary-table :data="$pages" per-page="10" striped hoverable>
            {{-- Bulk Actions --}}
            <@selectionselected>
                <x-mary-button icon="o-trash" class="btn-error btn-sm" wire:click="deleteSelected">
                    حذف انتخاب شده‌ها ({{ count($selected) }})
                </x-mary-button>
            </@selectionselected>

            <@column(label="عنوان" align="right" sortable key="title">
                <div class="font-bold">{{ $row->title }}</div>
                <div class="text-xs text-gray-500">{{ $row->slug }}</div>
            </@column>

            <@column(label="وضعیت" align="center">
                @switch($row->status)
                    @case('published')
                        <x-mary-badge label="منتشر شده" class="badge-success" />
                        @break
                    @case('archived')
                        <x-mary-badge label="آرشیو" class="badge-warning" />
                        @break
                    @default
                        <x-mary-badge label="پیش‌نویس" class="badge-ghost" />
                @endswitch
            </@column>

            <@column(label="ترتیب" align="center">{{ $row->sort }}</@column>

            <@column(label="زیردسته‌ها" align="center">
                <span class="badge badge-outline">{{ $row->children->count() }}</span>
            </@column>

            <@column(label="عملیات" align="center">
                <x-mary-button icon="o-pencil-square" wire:click="editPage({{ $row->id }})" class="btn-ghost btn-sm" />
                <x-mary-button icon="o-trash" wire:click="deletePage({{ $row->id }})" class="btn-ghost btn-sm text-error" wire:confirm="آیا از حذف این صفحه اطمینان دارید؟" />
            </@column>
        </x-mary-table>
    </x-mary-card>

    {{-- Create Modal --}}
    <x-mary-modal wire:model="showCreateModal" title="ایجاد صفحه جدید" size="xl" separator>
        <div class="grid grid-cols-2 gap-4">
            <x-mary-input label="عنوان" wire:model="title" placeholder="عنوان صفحه" required />
            <x-mary-input label="Slug" wire:model="slug" placeholder="url صفحه" dir="ltr" />
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <x-mary-select label="وضعیت" wire:model="status" :options="[
                ['id' => 'draft', 'name' => 'پیش‌نویس'],
                ['id' => 'published', 'name' => 'منتشر شده'],
                ['id' => 'archived', 'name' => 'آرشیو'],
            ]" />
            <x-mary-select label="صفحه والد" wire:model="parent_id" :options="\App\Models\Page::whereNull('parent_id')->pluck('title', 'id')->prepend('بدون والد', 0)->toArray()" />
            <x-mary-input label="ترتیب" wire:model="sort" type="number" min="0" />
        </div>

        <div class="mt-4">
            <x-mary-textarea label="محتوا" wire:model="content" rows="8" placeholder="محتوای صفحه را وارد کنید..." />
        </div>

        <x-slot:actions>
            <x-mary-button label="انصراف" wire:click="showCreateModal = false" />
            <x-mary-button label="ذخیره" icon="o-check" class="btn-primary" wire:click="storePage" />
        </x-slot:actions>
    </x-mary-modal>

    {{-- Edit Modal --}}
    <x-mary-modal wire:model="showEditModal" title="ویرایش صفحه" size="xl" separator>
        <input type="hidden" wire:model="pageId">
        <div class="grid grid-cols-2 gap-4">
            <x-mary-input label="عنوان" wire:model="title" placeholder="عنوان صفحه" required />
            <x-mary-input label="Slug" wire:model="slug" placeholder="url صفحه" dir="ltr" />
        </div>

        <div class="grid grid-cols-3 gap-4 mt-4">
            <x-mary-select label="وضعیت" wire:model="status" :options="[
                ['id' => 'draft', 'name' => 'پیش‌نویس'],
                ['id' => 'published', 'name' => 'منتشر شده'],
                ['id' => 'archived', 'name' => 'آرشیو'],
            ]" />
            <x-mary-select label="صفحه والد" wire:model="parent_id" :options="\App\Models\Page::whereNull('parent_id')->where('id', '!=', $pageId)->pluck('title', 'id')->prepend('بدون والد', 0)->toArray()" />
            <x-mary-input label="ترتیب" wire:model="sort" type="number" min="0" />
        </div>

        <div class="mt-4">
            <x-mary-textarea label="محتوا" wire:model="content" rows="8" placeholder="محتوای صفحه را وارد کنید..." />
        </div>

        <x-slot:actions>
            <x-mary-button label="انصراف" wire:click="showEditModal = false" />
            <x-mary-button label="بروزرسانی" icon="o-check" class="btn-primary" wire:click="updatePage" />
        </x-slot:actions>
    </x-mary-modal>

    {{-- Notifications --}}
    <x-mary-toast />
</div>