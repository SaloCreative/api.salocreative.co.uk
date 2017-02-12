<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Module extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label', 'active'
    ];

    protected $casts = [
        'available' => 'boolean',
        'active' => 'boolean',
        'required' => 'boolean',
        'userLevel' => 'integer'
    ];
}