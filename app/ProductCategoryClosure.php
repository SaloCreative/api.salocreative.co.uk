<?php
namespace App;

use Franzose\ClosureTable\Models\ClosureTable;

class ProductCategoryClosure extends ClosureTable implements ProductCategoryClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_category_closure';
}
