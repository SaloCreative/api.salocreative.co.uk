<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class ProductsController extends Controller
{

    public function index(Request $request)
    {
        // Ordering
        $orderByDirectionsAllowed = [ 'ASC', 'DESC' ];
        $orderByColumn = !empty($request->query('orderBy')) ? $request->query('orderBy') : 'id';
        $orderByDirection = !empty($request->query('orderByDirection')) ? $request->query('orderByDirection') : $orderByDirectionsAllowed[0];
        if (in_array($orderByDirection, $orderByDirectionsAllowed) === false) {
            $orderByDirection = $orderByDirectionsAllowed[0];
        }

        $products = Product::orderBy($orderByColumn, $orderByDirection);
        $perPage = !empty($request->query('perPage')) ? $request->query('perPage') : 9999;
        $products = $products->paginate($perPage);
        $products->appends(Input::except('page'));

        return $products;
    }

    public function create(Request $request)
    {
        $data = Input::all();
        $product = new Product();

        $response = new Response();

        // Validate Data
        $validation = $product->validate($data, 'create', false);
        if (is_bool($validation) && $validation) {

            $creatingUser = User::byToken($request->header('x-api-token'))->firstOrFail();
            $product->fill($data);
            $saved = $product->save();

            if ($saved === true) {
                $response->setStatusCode(Response::HTTP_CREATED);
                $response->headers->set('Location', route('page', $product->id));
                $response->setContent($this->show($product->id));
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

    public function show($productID)
    {
        $product = Product::findOrFail($productID);
        return $product;
    }

    public function delete($productID)
    {
        $product = Product::findOrFail($productID);
        $deleted = $product->delete();

        return ['status' => $deleted];
    }

    public function update(Request $request, $productID)
    {
        $data = Input::all();
        $product = Product::findOrFail($productID);
        $response = new Response();

        // Validate Data
        $validation = $product->validate($data, 'update', $productID);
        if (is_bool($validation) && $validation) {

            $editingUser = User::byToken($request->header('x-api-token'))->firstOrFail();
            $product->fill($data);
            $product->editor()->associate($editingUser);
            $saved = $product->save();

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


}
