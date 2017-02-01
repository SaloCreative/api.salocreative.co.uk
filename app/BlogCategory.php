<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class BlogCategory extends Model
{

    protected $dateFormat = 'U';

    protected $table = 'page_closure';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'online', 'seo_title', 'seo_description'
    ];

    protected $casts = [
        'online' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('online', '=', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('online', '=', false);
    }
}