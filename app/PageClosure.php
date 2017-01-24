<?php
namespace App;

use Franzose\ClosureTable\Models\ClosureTable;

class pageClosure extends ClosureTable implements pageClosureInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_closure';
}
