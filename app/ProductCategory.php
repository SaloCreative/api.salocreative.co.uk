<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Franzose\ClosureTable\Models\Entity;

class ProductCategory extends Entity implements ProductCategoryInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'product_categories';
    protected $dateFormat = 'U';

    /**
     * ClosureTable model instance.
     *
     * @var ProductCategoryClosure
     */
    protected $closure = 'App\ProductCategoryClosure';

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

    public function posts()
    {
        return $this->hasMany(Product::class, App::make(ProductCategory::class)->getKeyName());
    }
}
