<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFileTrait

{

    // upload image
    public function uploadImage($request)
    {

        // Save the file to disk
        $path = $request->file('banner')->store('images', 'public');

        // Get the public URL for accessing the uploaded file
        $url = Storage::url($path);

        $file = $request->file('banner');
        $originName = $file->getClientOriginalName();
        $originExt = $file->extension();

        // $fileName = time() . '.' . $originExt;
        // $file->storeAs('public/images/', $fileName);
        // $upload = $file->move(public_path('assets'), $fileName);
        // $file->storeAs('public/assets', $fileName);
        // return asset('public/assets/'. $fileName);
        // $request->file('banner')->storeAs('assets', $fileName);


        return [
            'path' => $url,
            'name' => $originName,
            'ext' => $originExt,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
            'url' => url($url)
        ];

    }
    
}
