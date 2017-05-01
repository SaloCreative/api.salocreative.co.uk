<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Media;

class ProductGalleryController extends Controller
{

    public function gallery($productID)
    {
        $items = DB::table('product_media')->where('product_id', '=', $productID)->orderBy('order', 'ASC')->get();
        $gallery = array();
        foreach ($items as $item) {
            $image = Media::findOrFail($item->id);
            array_push($gallery, $image);
        }
        return $gallery;
    }


}
