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
