<?php

namespace App\Http\Controllers;

use App\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class BlogCategoriesController extends Controller
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

        $categories = BlogCategory::orderBy($orderByColumn, $orderByDirection);
        $perPage = !empty($request->query('perPage')) ? $request->query('perPage') : 9999;
        $categories = $categories->paginate($perPage);
        $categories->appends(Input::except('page'));

        return $categories;
    }

    /*public function create(Request $request)
    {
        $data = Input::all();
        $creatingUser = User::byToken($request->header('x-api-token'))->firstOrFail();

        $blog = new Blog();
        $blog->fill($data);

        $saved = $blog->save();

        $response = new Response();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->headers->set('Location', route('page', $blog->id));
            $response->setContent($this->show($blog->id));
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function show($blogID)
    {
        $blog = Blog::findOrFail($blogID);
        return $blog;
    }

    public function update(Request $request, $blogID)
    {
        $data = Input::all();
        $editingUser = User::byToken($request->header('x-api-token'))->firstOrFail();

        $blog = Blog::findOrFail($blogID);
        $blog->fill($data);
        $blog->editor()->associate($editingUser);

        $saved = $blog->save();

        $response = new Response();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function testBlog(Request $request)
    {
        $blogID = $request->query('id');
        if(!empty($request->query('direction'))) {
            $direction = $request->query('direction');
            $blog  = Blog::findOrFail($blogID);
            if($direction == 'up') {
                $sibling = $blog->getPrevSiblings()->last();
            } else {
                $sibling = $blog->getNextSiblings()->first();
            }
            if(isset($sibling)) {
                $sibling->position;
                return $sibling;
                //$blog->position = $sibling->position;
                //$blog->save();
            }
        }
    }*/

}
