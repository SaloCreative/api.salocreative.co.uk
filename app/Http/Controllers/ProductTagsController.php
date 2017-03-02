<?php

namespace App\Http\Controllers;

use App\ProductTag;
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
        if (is_bool($validation)) {

            $creatingUser = User::byToken($request->header('x-api-token'))->firstOrFail();
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

        $// Validate Data
        $validation = $productTag->validate($data, 'update', $productID);
        if (is_bool($validation)) {

            $editingUser = User::byToken($request->header('x-api-token'))->firstOrFail();
            $productTag->fill($data);
            $productTag->editor()->associate($editingUser);
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

}
