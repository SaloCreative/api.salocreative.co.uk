<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use SoftDeletes;
    protected $dateFormat = 'U';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'sku', 'content', 'category_id', 'inStock', 'online', 'author', 'editor', 'seo_title', 'seo_description'
    ];

    protected $casts = [
        'online' => 'boolean',
        'author' => 'integer',
        'editor' => 'integer',
        'inStock' => 'integer',
        'category_id' => 'integer'
    ];

    private $rules = array(
        'title' => 'required',
        'slug'  => 'required|unique:products',
        'sku'  => 'required|unique:products'
    );

    public function validate($data)
    {
        $validator = Validator::make($data, $this->rules);
        if ($validator->fails()) {
            return $validator->messages();
        } else {
            return true;
        }
    }

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