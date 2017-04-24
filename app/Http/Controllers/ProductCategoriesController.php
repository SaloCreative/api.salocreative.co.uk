<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class ProductCategoriesController extends Controller
{

    public function tree()
    {
        $tree = ProductCategory::getTree()->sortBy('title');
        return $tree->values()->all();
    }

    public function index()
    {
        $result = array();
        $roots = ProductCategory::getRoots()->sortBy('title');
        foreach($roots as $parent) {
            array_push($result, $parent);

            $productCategory = ProductCategory::find($parent->id);

            if ($productCategory->hasChildren()) {
                $childTree = $this->buildFlatTree($productCategory);
                array_push($result, $childTree);
            }
        }

        $objTmp = (object) array('aFlat' => array());
        array_walk_recursive($result, create_function('&$v, $k, &$t', '$t->aFlat[] = $v;'), $objTmp);
        return $objTmp->aFlat;
    }

    public function buildFlatTree($productCategory)
    {
        $result = array();

        $children = $productCategory->getChildren()->sortBy('title');

        foreach($children as $child) {
            array_push($result, $child);

            $productCategory = ProductCategory::find($child->id);
            if ($productCategory->hasChildren()) {
                $childTree = $this->buildFlatTree($productCategory);
                array_push($result, $childTree);
            }
        }

        return $result;
    }

    public function create(Request $request)
    {
        $data = Input::all();
        $productCategory = new ProductCategory();
        $response = new Response();

        // Validate Data
        $validation = $productCategory->validate($data, 'create', false);

        if (is_bool($validation) && $validation) {

            $productCategory->fill($data);

            if(!empty($data['parent_id'])) {
                $parent = ProductCategory::find($data['parent_id']);
                $parent->addChild($productCategory);
                $saved = true;
            } else {
                $saved = $productCategory->save();
            }

            if ($saved === true) {
                $response->setStatusCode(Response::HTTP_CREATED);
                $response->headers->set('Location', route('page', $productCategory->id));
                $response->setContent($this->show($productCategory->id));
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

    public function show($productCategoryID)
    {
        $productCategory = ProductCategory::findOrFail($productCategoryID);
        $dimensionFields = array();
        foreach($productCategory->dimensionFields as $dimensionField) {
            array_push($dimensionFields, $dimensionField);
        }
        $result = (object)[
            'category' => $productCategory,
            'dimensions' => $dimensionFields

        ];
        return $productCategory;
    }

    public function update(Request $request, $productCategoryID)
    {
        $data = Input::all();
        $productCategory = ProductCategory::findOrFail($productCategoryID);
        $response = new Response();

        // Validate Data
        $validation = $productCategory->validate($data, 'update', $productCategoryID);

        if (is_bool($validation) && $validation) {
            $productCategory->fill($data);

            if(isset($data['parent_id']) && $data['parent_id'] !== $productCategoryID) {
                $parentID = $data['parent_id'];
                $descendants = $productCategory->getDescendantsWhere('id', '=', $parentID);
                if($descendants->isEmpty()) {
                    if (!empty($parentID)) {
                        $productCategory->moveTo(0, ProductCategory::find($parentID));
                    } else {
                        $productCategory->makeRoot(0);
                    }
                }
            }
            $saved = $productCategory->save();

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

    public function delete($productCategoryID)
    {
        $productCategory = ProductCategory::findOrFail($productCategoryID);
        if($productCategory->hasChildren()) {
            $children = $productCategory->getChildren();
            if($productCategory->parent_id) {
                foreach($children as $child) {
                    $child->moveTo(0, ProductCategory::find($productCategory->parent_id));
                }
            } else {
                foreach($children as $child) {
                    $child->makeRoot(0);
                }
            }
        }

        $deleted = $productCategory->delete();

        return ['status' => $deleted];
    }
}
