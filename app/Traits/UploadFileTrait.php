<?php

namespace App\Traits;

use App\Utils\ImageKit;
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


    public function uploadToImageKit($request, $fileName, $fileType = 'image')
    {

        // return "uploadToImageKit";
        if (!$request->file($fileName)) {
            return;
        }

        $file = $request->file($fileName);
        $originName = $file->getClientOriginalName();
        $originExt = $file->extension();

        $upload = new ImageKit();
        $res =  $upload->uploadFile($file, $fileType);

        if (!$res['success'] == true) {
            return;
        }

        return [
            'original_name' => $originName,
            'name' => $fileName,
            'type' => $file->getMimeType(),
            'path' => $res['data']['filePath'],
            'file_id' => $res['data']['fileId'],
            'url' => $res['data']['url'],
            'size' => $file->getSize(),
            'hosted_at' => 'imagekit', // cloudinary | imagekit
        ];

    }

}
