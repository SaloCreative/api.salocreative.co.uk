<?php

namespace App\Http\Controllers;

use App\Product;
use App\DimensionField;
use App\ProductCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class DimensionFieldsController extends Controller
{

    public function index(Request $request)
    {
        // Ordering
        $orderByDirectionsAllowed = [ 'ASC', 'DESC' ];
        $orderByColumn = !empty($request->query('orderBy')) &&  $request->query('orderBy') !== 'undefined' ? $request->query('orderBy') : 'id';
        $orderByDirection = !empty($request->query('orderByDirection')) ? $request->query('orderByDirection') : $orderByDirectionsAllowed[1];
        if (in_array($orderByDirection, $orderByDirectionsAllowed) === false) {
            $orderByDirection = $orderByDirectionsAllowed[1];
        }

        $dimensionFields = DimensionField::orderBy($orderByColumn, $orderByDirection);
        $perPage = !empty($request->query('perPage')) && $request->query('perPage') !== 'undefined' ? $request->query('perPage') : 9999;
        $dimensionFields = $dimensionFields->paginate($perPage);
        $dimensionFields->appends(Input::except('page'));

        return $dimensionFields;
    }

    public function create()
    {
        $data = Input::all();
        $categoriesAdd = $data['categories'];

        $dimensionField = new DimensionField();

        $response = new Response();

        $dimensionField->fill($data);
        $saved = $dimensionField->save();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->headers->set('Location', route('page', $dimensionField->id));
            $response->setContent($this->show($dimensionField->id));
            if(!empty($categoriesAdd)) {
                foreach($categoriesAdd as $category) {
                    $this->assignCategory($category, $dimensionField->id);
                }
            }
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function show($dimensionFieldID)
    {
        $dimensionField = DimensionField::findOrFail($dimensionFieldID);
        return $dimensionField;
    }

    public function delete($dimensionFieldID)
    {
        $dimensionField = DimensionField::findOrFail($dimensionFieldID);
        $deleted = $dimensionField->delete();

        return ['status' => $deleted];
    }

    public function update($dimensionFieldID)
    {
        $data = Input::all();
        $categoriesAdd = $data['categoriesAdd'];
        $categoriesRemove = $data['categoriesRemove'];

        $dimensionField = DimensionField::findOrFail($dimensionFieldID);
        $response = new Response();

        $dimensionField->fill($data);
        $saved = $dimensionField->save();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
            if(!empty($categoriesAdd)) {
                foreach($categoriesAdd as $category) {
                    $this->assignCategory($category, $dimensionFieldID);
                }
            }

            if(!empty($categoriesRemove)) {
                foreach($categoriesRemove as $category) {
                    $this->removeCategory($category, $dimensionFieldID);
                }
            }

            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function assignCategory($category, $dimensionFieldID)
    {
        $dimensionField = DimensionField::findOrFail($dimensionFieldID);

        $categoryID = intval($category);
        $response = new Response();

        if (!empty($dimensionField) && !empty($categoryID)) {
            $category = ProductCategory::findOrFail($categoryID);
            if (!$dimensionField->categories->contains($category->id)) {
                $dimensionField->categories()->attach($category);
            }
        }
    }

    public function removeCategory($category, $dimensionFieldID)
    {
        $dimensionField = DimensionField::findOrFail($dimensionFieldID);

        $categoryID = intval($category);

        if (!empty($dimensionField) && !empty($categoryID)) {
            $category = ProductCategory::findOrFail($categoryID);
            $dimensionField->categories()->detach($category);
        }
    }

    public function assign(Request $request, $dimensionFieldID)
    {
        $dimensionField = DimensionField::findOrFail($dimensionFieldID);

        $categoryID = intval(json_decode($request->getContent())->category);
        $response = new Response();

        if (!empty($dimensionField) && !empty($categoryID)) {
            $category = ProductCategory::findOrFail($categoryID);
            if (!$dimensionField->categories->contains($category->id)) {
                $dimensionField->categories()->attach($category);
            }
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        } else {
            $response->setContent([ 'error' => 'Unknown error' ]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response;

    }

    public function remove(Request $request, $dimensionFieldID)
    {
        $dimensionField = DimensionField::findOrFail($dimensionFieldID);

        $categoryID = intval(json_decode($request->getContent())->category);
        $response = new Response();

        if (!empty($dimensionField) && !empty($categoryID)) {
            $category = ProductCategory::findOrFail($categoryID);
            $dimensionField->categories()->detach($category);
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        } else {
            $response->setContent([ 'error' => 'Unknown error' ]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response;

    }

}
