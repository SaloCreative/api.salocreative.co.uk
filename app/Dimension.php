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
        'dimension', 'field', 'product_id'
    ];

    protected $appends = ['dimension_field'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function dimensionField()
    {
        $dimensionField = DimensionField::findOrFail($this->field);
        return $dimensionField;
    }

    public function getDimensionFieldAttribute()
    {
        return $this->dimensionField();
    }

}