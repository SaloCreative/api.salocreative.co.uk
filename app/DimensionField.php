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

    protected $appends = ['categories'];

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }


    public function dimensions()
    {
        return $this->hasMany(Dimension::class);
    }

    public function getCategoriesAttribute()
    {
        return $this->categories()->get();
    }
}