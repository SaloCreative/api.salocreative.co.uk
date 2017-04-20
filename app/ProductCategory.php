<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Franzose\ClosureTable\Models\Entity;
use Illuminate\Support\Facades\Validator;

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

    private $rules = [
        'create' => [
            'title' => 'required',
            'slug'  => 'required|unique:product_categories,slug'
        ],
        'update' => [
            'title' => 'required',
            'slug'  => 'required|unique:product_categories,slug,:id'
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

    public function products()
    {
        return $this->hasMany(Product::class, App::make(ProductCategory::class)->getKeyName());
    }

    public function dimensionFields()
    {
        return $this->hasMany(DimensionField::class);
    }
}
