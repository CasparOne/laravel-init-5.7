<?php

namespace App\Http\Controllers;

use App\Image;
use App\Jobs\ProccessImageThumbnails;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        return view('upload_form');
    }

    public function upload(Request $request)
    {
            $this->validate($request, [
                'demo_image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            $image = $request->file('demo_image');
            $input['demo_image'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $input['demo_image']);

            $image = new Image();
            $image->org_path = 'images' . DIRECTORY_SEPARATOR . $input['demo_image'];
            $image->save();

            ProccessImageThumbnails::dispatch($image);

            return \Redirect::to('image/index')->with('message', 'Image upload successfully!');
    }

}
