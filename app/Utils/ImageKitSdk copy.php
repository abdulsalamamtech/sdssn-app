<?php


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use ImageKit\ImageKit;

class ImageKitSdk extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file1');

        if (!$file->isValid()) {
            // Handle invalid file upload
            return response()->json(['error' => 'Invalid file'], 400);
        }

        $uploadResponse = ImageKit::upload([
            'file' => $file,
            'fileName' => 'abc1.jpg', // Or use a unique name generator
            'tags' => ['tag1'],
            'folder' => 'sdssn-app'
        ]);

        if ($uploadResponse['error']) {
            // Handle upload errors
            return response()->json(['error' => $uploadResponse['error']], 500);
        }

        $imageURL = ImageKit::url([
            'src' => $uploadResponse['url'],
            'transformation' => [
                [
                    'height' => 300,
                    'width' => 400,
                ],
            ],
        ]);

        return response()->json([
            'url' => $imageURL,
            'message' => 'Image uploaded successfully!',
        ]);
    }
}




// {
//   "fileId": "6673f88237b244ef54d60180",
//   "name": "test-image.jpg",
//   "size": 117079,
//   "versionInfo": {
//     "id": "6673f88237b244ef54d60180",
//     "name": "Version 1"
//   },
//   "filePath": "/test-image.jpg",
//   "url": "https://ik.imagekit.io/demo/test-image.jpg",
//   "fileType": "image",
//   "height": 500,
//   "width": 1000,
//   "orientation": 1,
//   "thumbnailUrl": "https://ik.imagekit.io/demo/tr:n-ik_ml_thumbnail/test-image.jpg"
// }
