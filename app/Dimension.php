<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dimension'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function dimension_field()
    {
        return $this->belongsTo(DimensionField::class);
    }

}