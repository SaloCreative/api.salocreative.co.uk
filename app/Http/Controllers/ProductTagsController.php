<?php

namespace App\Http\Controllers;

use App\ProductTag;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class ProductTagsController extends Controller
{

    public function index(Request $request)
    {
        // Ordering
        $orderByDirectionsAllowed = [ 'ASC', 'DESC' ];
        $orderByColumn = !empty($request->query('orderBy')) &&  $request->query('orderBy') !== 'undefined' ? $request->query('orderBy') : 'title';
        $orderByDirection = !empty($request->query('orderByDirection')) ? $request->query('orderByDirection') : $orderByDirectionsAllowed[0];
        if (in_array($orderByDirection, $orderByDirectionsAllowed) === false) {
            $orderByDirection = $orderByDirectionsAllowed[0];
        }

        $tags = ProductTag::orderBy($orderByColumn, $orderByDirection);
        $perPage = !empty($request->query('perPage')) && $request->query('perPage') !== 'undefined' ? $request->query('perPage') : 9999;
        $tags = $tags->paginate($perPage);
        $tags->appends(Input::except('page'));

        return $tags;
    }

    public function create(Request $request)
    {
        $data = Input::all();
        $productTag = new ProductTag();

        $response = new Response();

        // Validate Data
        $validation = $productTag->validate($data, 'create', false);
        if (is_bool($validation) && $validation) {

            $productTag->fill($data);
            $saved = $productTag->save();

            if ($saved === true) {
                $response->setStatusCode(Response::HTTP_CREATED);
                $response->headers->set('Location', route('page', $productTag->id));
                $response->setContent($this->show($productTag->id));
                return $response;
            }

        } else {
            $response->setContent($validation);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function show($productTagID)
    {
        $productTag = ProductTag::findOrFail($productTagID);
        return $productTag;
    }

    public function delete($productTagID)
    {
        $productTag = ProductTag::findOrFail($productTagID);
        $deleted = $productTag->delete();

        return ['status' => $deleted];
    }

    public function update(Request $request, $productTagID)
    {
        $data = Input::all();

        $productTag = ProductTag::findOrFail($productTagID);
        $response = new Response();

        // Validate Data
        $validation = $productTag->validate($data, 'update', $productTagID);
        if (is_bool($validation) && $validation) {

            $productTag->fill($data);
            $saved = $productTag->save();

            if ($saved === true) {
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
                return $response;
            }

        } else {
            $response->setContent($validation);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function assign(Request $request, $productTagID)
    {
        $data = Input::all();
        $productTag = ProductTag::findOrFail($productTagID);

        $response = new Response();

        if(!empty($data['productID'])) {
            $productID = intval($data['productID']);
            $product = Product::findOrFail($productID);
            if (!$productTag->products->contains($product->id)) {
                $productTag->products()->attach($productID);
            }
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        } else {
            $response->setContent([ 'error' => 'Couldn\'t associate tag' ]);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    public function unassign(Request $request, $productTagID)
    {
        $data = Input::all();
        $productTag = ProductTag::findOrFail($productTagID);

        $response = new Response();

        if(!empty($data['productID'])) {
            $product = intval($data['productID']);
            $productTag->products()->detach($product);
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        } else {
            $response->setContent([ 'error' => 'No product set' ]);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    public function bulkadd($productID)
    {
        $tags = Input::all();
        $product = Product::findOrFail($productID);
        $response = new Response();

        foreach($tags as $item) {
            $productTag = ProductTag::findOrFail($item['id']);
            if (!$productTag->products->contains($product->id)) {
                $productTag->products()->attach($productID);
            }
        }

        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        return $response;
    }

    public function bulkremove($productID)
    {
        $tags = Input::all();
        $product = Product::findOrFail($productID);
        $response = new Response();

        foreach($tags as $item) {
            $productTag = ProductTag::findOrFail($item['id']);
            if ($productTag->products->contains($product->id)) {
                $productTag->products()->detach($productID);
            }
        }

        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        return $response;
    }

    public function bulkaddremove($productID)
    {
        $tags = Input::all();
        $product = Product::findOrFail($productID);
        $response = new Response();

        $currentTags = $product->tags;

        if(count($currentTags) > 0) {
            foreach($currentTags as $currentTag) {
                $productTag = ProductTag::findOrFail($currentTag['id']);
                if ($productTag->products->contains($product->id)) {
                    $productTag->products()->detach($productID);
                }
            }
        }
        foreach($tags as $item) {
            $productTag = ProductTag::findOrFail($item['id']);
            if (!$productTag->products->contains($product->id)) {
                $productTag->products()->attach($productID);
            }
        }

        $response->setStatusCode(Response::HTTP_NO_CONTENT);
        return $response;
    }

}
