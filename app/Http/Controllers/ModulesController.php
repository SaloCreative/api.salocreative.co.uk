<?php

namespace App\Http\Controllers;

use App\Module;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class ModulesController extends Controller
{

    public function index(Request $request)
    {
        // Ordering
        $orderByDirectionsAllowed = [ 'ASC', 'DESC' ];
        $orderByColumn = !empty($request->query('orderBy')) ? $request->query('orderBy') : 'module';
        $orderByDirection = !empty($request->query('orderByDirection')) ? $request->query('orderByDirection') : $orderByDirectionsAllowed[0];
        if (in_array($orderByDirection, $orderByDirectionsAllowed) === false) {
            $orderByDirection = $orderByDirectionsAllowed[0];
        }

        $available = !empty($request->query('available')) ? $request->query('available') : 1;

        $modules = Module::orderBy($orderByColumn, $orderByDirection)->where('available', '=', $available)->get();
        return $modules;
    }

    public function update(Request $request, $moduleID)
    {
        $data = Input::all();
        $module = Module::findOrFail($moduleID);
        $module->fill($data);

        $saved = $module->save();

        $response = new Response();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function showByName($moduleName)
    {
        $module = Module::where('module', '=', $moduleName)->firstOrFail();
        return $module;
    }
}
