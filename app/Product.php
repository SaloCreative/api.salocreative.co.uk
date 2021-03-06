<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Media;

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
        'title', 'slug', 'sku', 'content', 'category_id', 'inStock', 'price', 'online', 'featured', 'author', 'editor', 'seo_title', 'seo_description', 'featured_image'
    ];

    protected $casts = [
        'online' => 'boolean',
        'author' => 'integer',
        'editor' => 'integer',
        'inStock' => 'integer',
        'featured' => 'boolean',
        'category_id' => 'integer'
    ];

    protected $appends = ['tags', 'dimensions', 'main_image', 'gallery'];

    private $rules = [
        'create' => [
            'title' => 'required',
            'slug'  => 'required|unique:products,slug',
            'sku'  => 'required|unique:products,sku'
        ],
        'update' => [
            'title' => 'required',
            'slug'  => 'required|unique:products,slug,:id',
            'sku'  => 'required|unique:products,sku,:id'
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

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor', App::make(User::class)->getKeyName());
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author', App::make(User::class)->getKeyName());
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class);
    }

    public function getTagsAttribute()
    {
        return $this->tags()->get();
    }

    public function dimensions()
    {
        return $this->hasMany(Dimension::class);
    }

    public function getDimensionsAttribute()
    {
        return $this->dimensions()->get();
    }

    public function getMainImageAttribute()
    {
       if(!empty($this->featured_image)) {
            $featuredImage = Media::findOrFail($this->featured_image);
            return  $featuredImage;
        }
    }

    public function getGalleryAttribute()
    {
        $items = DB::table('product_media')->where('product_id', '=', $this->id)->orderBy('order', 'ASC')->get();
        $gallery = array();
        foreach ($items as $item) {
            $image = Media::findOrFail($item->media_id);
            $image->order = $item->order;
            array_push($gallery, $image);
        }
        return $gallery;
    }

}