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
        $time = time();
        $ext = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file_path = $name . '_' . $time . '.' . $ext;
        $basePath = __DIR__ . '/../../../public/assets/' . $year . '/' . $month;
        $savedFile = $basePath . '/' . $file_path;

        $file->move($basePath, $file_path);

        $media->title = $name;
        $media->slug = $name . '_' . $time;
        $media->folder = 'assets/' . $year . '/' . $month;
        $media->type = $ext;
        $media->mime = File::mimeType($savedFile);
        $media->file_size = File::size($savedFile);
        list($a, $b) = explode('/', $media->mime);

        if ($a == 'image') {
            $media->dimension_height = getimagesize($savedFile)[1];
            $media->dimension_width = getimagesize($savedFile)[0];

            $this->generateImageSizes($savedFile, $basePath, $name, $time, $ext);
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

    private function generateImageSizes($savedFile, $basePath, $name, $time, $ext)
    {
        $imageSizes = array(
            (object) [
                'label' => 'thumb',
                'width' => 150,
                'height' => 150,
                'constraint' => 'fit'
            ],
            (object) [
                'label' => 'medium',
                'width' => 350,
                'height' => 350,
                'constraint' => 'fit'
            ],
            (object) [
                'label' => 'large',
                'width' => 1030,
                'height' => null,
                'constraint' => 'resize'
            ],
            (object) [
                'label' => 'product_feature',
                'width' => 680,
                'height' => 480,
                'constraint' => 'fit'
            ],
            (object) [
                'label' => 'product_thumb',
                'width' => 400,
                'height' => 285,
                'constraint' => 'fit'
            ],
            (object) [
                'label' => 'blog_thumb',
                'width' => 450,
                'height' => 280,
                'constraint' => 'fit'
            ],
        );

        foreach ( $imageSizes as $image ) {
            $img = Image::make($savedFile);
            if($image->constraint == 'fit') {
                $img->fit($image->width, $image->height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img->resize($image->width, $image->height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $img->save($basePath . '/' . $name . '_' . $time . '_' . $image->label . '.' . $ext);
        }
    }
}
