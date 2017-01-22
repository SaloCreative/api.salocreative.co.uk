<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Page extends Model
{

    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'content', 'parent', 'online', 'inNav', 'isHome'
    ];

    protected $casts = [
        'parent' => 'integer',
        'online' => 'boolean',
        'inNav' => 'boolean',
        'isHome' => 'boolean'
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