<?php
namespace App;

use Franzose\ClosureTable\Models\Entity;

class ProductCategory extends Entity implements ProductCategoryInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_categories';

    /**
     * ClosureTable model instance.
     *
     * @var ProductCategoryClosure
     */
    protected $closure = 'App\ProductCategoryClosure';
}
