<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFileTrait

{

    // upload file
    public function uploadImage($request, $fileName = 'image')
    {

        // Save the file to disk
        $path = $request->file($fileName)->store('images', 'public');

        // Get the public URL for accessing the uploaded file
        $url = Storage::url($path);

        $file = $request->file($fileName);
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
            'original_name' => $originName,
            'type' => $file->getMimeType(),
            'name' => $fileName,
            // 'file_id' => $file->get,
            'url' => url($url),
            'size' => $file->getSize(),
            'hosted_at' => 'directory', // cloudinary | imagekit
            // 'ext' => $originExt,
        ];

    }


    // Update file
    public function updateImage($request, $fileName = 'image')
    {

        // Save the file to disk
        $path = $request->file($fileName)->store('images', 'public');

        // Get the public URL for accessing the uploaded file
        $url = Storage::url($path);

        $file = $request->file($fileName);
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
            'original_name' => $originName,
            'type' => $file->getMimeType(),
            'name' => $fileName,
            // 'file_id' => $file->get,
            'url' => url($url),
            'size' => $file->getSize(),
            'hosted_at' => 'directory', // cloudinary | imagekit
            // 'ext' => $originExt,
        ];

    }

}
