<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ProductTag extends Model
{
    use SoftDeletes;
    protected $dateFormat = 'U';

    protected $table = 'product_tags';

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

    private $rules = [
        'create' => [
            'title' => 'required',
            'slug'  => 'required|unique:product_tags,slug'
        ],
        'update' => [
            'title' => 'required',
            'slug'  => 'required|unique:product_tags,slug,:id'
        ]
    ];

    public function validate($data, $method, $id)
    {
        $currentRules = $this->buildValidationRules($method, $id);
        $validator = Validator::make($data, $currentRules);
        if ($validator->fails()) {
            return $validator->messages();
        } else {
            return true;
        }
    }

    private function buildValidationRules($method, $id) {
        $rules = $this->rules[$method];
        if ($id) {
            foreach ($rules as &$rule) {
                $rule = str_replace(':id', $id, $rule);
            }
        }
        return $rules;
    }

    public function scopeActive($query)
    {
        return $query->where('online', '=', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('online', '=', false);
    }

    public function posts()
    {
        return $this->hasMany(Product::class, App::make(ProductTag::class)->getKeyName());
    }
}