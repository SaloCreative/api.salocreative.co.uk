<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
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

    public function addImages($productID)
    {
        $data = Input::all();
        $product = Product::findOrFail($productID);
        if ($product && $data) {
           foreach($data as $item) {
               $record = DB::table('product_media')->where('product_id', '=', $productID)->where('media_id', '=', $item['id'])->get();
               if(!count($record)) {
                   DB::table('product_media')->insert(
                       [
                           'product_id' => $productID,
                           'media_id' => $item['id'],
                           'order' => $item['order'],
                           'updated_at' => time(),
                           'created_at' => time()
                       ]
                   );
               }
           }
        }
    }

    public function addImage($productID)
    {
        $data = Input::all();
        $product = Product::findOrFail($productID);
        if ($product && $data) {
            $record = DB::table('product_media')->where('product_id', '=', $productID)->where('media_id', '=', $data['id'])->get();
            if(!count($record)) {
                DB::table('product_media')->insert(
                    [
                        'product_id' => $productID,
                        'media_id' => $data['id'],
                        'order' => $data['order'],
                        'updated_at' => time(),
                        'created_at' => time()
                    ]
                );
            }
        }
    }

    public function orderImages($productID)
    {
        $data = Input::all();
        $product = Product::findOrFail($productID);

        if ($product && $data) {
            foreach ($data as $image) {
                DB::table('product_media')
                    ->where('product_id', '=', $productID)
                    ->where('media_id', '=', $image['id'])
                    ->update(['order' => $image['order']]);
            }
        }
    }

    public function removeImage($productID)
    {
        $data = Input::all();
        if ($data) {
            DB::table('product_media')->where('product_id', '=', $productID)->where('media_id', '=', $data['id'])->delete();
        }
    }
}
