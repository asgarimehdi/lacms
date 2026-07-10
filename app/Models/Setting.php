<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['site_name', 'site_tagline', 'contact_email', 'comments_enabled'];

    protected $casts = ['comments_enabled' => 'boolean'];

    // Singleton: always return the first row
    public static function current(): self
    {
        return static::first() ?? static::create([
            'site_name' => config('app.name'),
        ]);
    }
}