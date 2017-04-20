<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DimensionField extends Model
{
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label', 'type'
    ];

    public function categories()
    {
        return $this->belongsToMany(Product::class);
    }

    public function dimensions()
    {
        return $this->hasMany(Dimension::class);
    }

}