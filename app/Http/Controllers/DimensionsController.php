<?php

namespace App\Http\Controllers;

use App\Dimension;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

class DimensionsController extends Controller
{
    public function manage()
    {
        $data = Input::all();
        $newDim = $data['dimension'];
        $field = $data['field'];
        $product =$data['product_id'];
        $response = new Response();

        if ($product && $field) {
            $dimension = Dimension::where('product_id', '=', $product)->where('field', '=', $field)->first();

            if ($newDim){
                if (!$dimension) {
                    $dimension = new Dimension();
                }
                $dimension->fill($data);
                $saved = $dimension->save();

                if ($saved === true) {
                    $response->setStatusCode(Response::HTTP_CREATED);
                    $response->headers->set('Location', route('page', $dimension->id));
                    $response->setContent($this->show($dimension->id));
                    return $response;
                }

            } else if ($dimension) {
                $deleted = $dimension->delete();
                return ['status' => $deleted];
            }

        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;

    }

    public function show($dimensionID)
    {
        $dimension = Dimension::findOrFail($dimensionID);
        return $dimension;
    }

}
