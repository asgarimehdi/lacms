<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comment extends Model
{
    protected $fillable = ['post_id', 'author_name', 'author_email', 'body', 'is_approved'];

    protected $casts = ['is_approved' => 'boolean'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}