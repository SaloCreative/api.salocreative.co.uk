<?php

namespace App\Http\Controllers;

use App\Page;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class PagesController extends Controller
{

    public function index(Request $request)
    {
        // Ordering
        $orderByDirectionsAllowed = [ 'ASC', 'DESC' ];
        $orderByColumn = !empty($request->query('orderBy')) ? $request->query('orderBy') : 'id';
        $orderByDirection = !empty($request->query('orderByDirection')) ?: $orderByDirectionsAllowed[0];
        if (!in_array($orderByDirection, $orderByDirectionsAllowed) === false) {
            $orderByDirection = $orderByDirectionsAllowed[0];
        }

        $pages = Page::orderBy($orderByColumn, $orderByDirection);
        $perPage = $request->query('perPage') ?:9999;
        $pages = $pages->paginate($perPage);
        $pages->appends(Input::except('page'));

        return $pages;
    }

    public function create(Request $request)
    {
        $data = Input::all();
        $creatingUser = User::byToken($request->header('x-api-token'))->firstOrFail();

        $page = new Page();
        $page->fill($data);
        // $page->author()->associate($creatingUser);

        if(!empty($data['parent_id'])) {
            $parent = Page::find($data['parent_id']);
            $parent->addChild($page);
            $saved = true;
        } else {
            $saved = $page->save();
        }

        $response = new Response();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->headers->set('Location', route('page', $page->id));
            $response->setContent($this->show($page->id));
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function show($pageID)
    {
        $page = Page::findOrFail($pageID);
        return $page;
    }

    public function update(Request $request, $pageID)
    {
        $data = Input::all();
        $editingUser = User::byToken($request->header('x-api-token'))->firstOrFail();

       $page = Page::findOrFail($pageID);
       $page->fill($data);
       $page->editor()->associate($editingUser);
        if(isset($data['parent_id'])) {
            if(!empty($data['parent_id'])) {
                $page->moveTo(0, Page::find($data['parent_id']));
            } else {
                $page->makeRoot(99);
            }
        }
        $saved = $page->save();

        $response = new Response();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

}
