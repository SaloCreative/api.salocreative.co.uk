<?php

namespace App\Http\Controllers;

use App\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use Intervention\Image\ImageManagerStatic as CustomImage;
CustomImage::configure(array('driver' => 'gd'));

class MediaController extends Controller
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

        $media = Media::orderBy($orderByColumn, $orderByDirection);
        $perPage = !empty($request->query('perPage')) ? $request->query('perPage') : 9999;
        $media = $media->paginate($perPage);
        $media->appends(Input::except('page'));

        return $media;
    }

    public function create(Request $request)
    {

        $media = new Media();
        $file = $request->file('file');

        $year = date('Y');
        $month = date('m');

        $basePath = __DIR__.'/../../../public/assets/'.$year.'/'.$month;

        $file->move($basePath, pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . time() . '.' . $file->getClientOriginalExtension());
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file_path = $filename . '-' . time() . '.' . $file->getClientOriginalExtension();

        $savedFile = $basePath.'/'.$file_path;

        $media->title = $filename;
        $media->slug = $file_path;
        $media->type = $file->getClientOriginalExtension();
        $media->mime = File::mimeType($savedFile);
        $media->file_size = File::size($savedFile);
        list($a, $b) = explode('/', $media->mime);
        if ($a == 'image') {
            $media->dimension_height = getimagesize($savedFile)[1];
            $media->dimension_width = getimagesize($savedFile)[0];
            $img = Image::make($savedFile);
            $img->fit(350, 350, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($basePath.'/thumb_'.$file_path);
        }

        $saved = $media->save();
        $response = new Response();

        if ($saved === true) {
            $response->setStatusCode(Response::HTTP_CREATED);
            return $response;
        }

        $response->setContent([ 'error' => 'Unknown error' ]);
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        return $response;
    }

    public function show($mediaID)
    {
        $media = Media::findOrFail($mediaID);
        return $media;
    }

    public function update(Request $request, $mediaID)
    {
        $data = Input::all();

        $media = Media::findOrFail($mediaID);
        $media->fill($data);

        $saved = $media->save();

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
