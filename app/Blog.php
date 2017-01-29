<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Blog extends Model
{

    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'content', 'online', 'author', 'editor', 'seo_title', 'seo_description'
    ];

    protected $casts = [
        'online' => 'boolean',
        'author' => 'integer',
        'editor' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('online', '=', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('online', '=', false);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor', App::make(User::class)->getKeyName());
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author', App::make(User::class)->getKeyName());
    }
}