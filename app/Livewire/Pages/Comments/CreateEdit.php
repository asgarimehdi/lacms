<?php

namespace App\Livewire\Pages\Comments;

use App\Models\Comment;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateEdit extends Component
{
    use Toast;

    public Comment $comment;

    public function mount(Comment $comment): void
    {
        $this->comment = $comment->load('post');
    }

    public function approve(): void
    {
        $this->comment->update(['is_approved' => true]);
        $this->success('Comment approved.');
        $this->redirect('/admin/comments');
    }

    public function reject(): void
    {
        $this->comment->delete();
        $this->success('Comment rejected and deleted.');
        $this->redirect('/admin/comments');
    }

    public function render()
    {
        return view('pages.comments.create-edit');
    }
}
