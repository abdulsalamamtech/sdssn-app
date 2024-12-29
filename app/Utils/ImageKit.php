<?php

namespace App\Utils;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Imagekit
{

    // Upload File
    public function uploadFile($file, $fileType = 'file', $tags = 'file')
    {

        //  private_key//ewq8iwfkdsvckjvcndxcv
        // $privateKey = env('IMAGE_KIT_PRIVATE_KEY');
        // 'https://upload.imagekit.io/api/v1/files/upload';
        // $uploadUrl = env('IMAGE_KIT_UPLOAD_URL');



        $secret = env('IMAGE_KIT_PRIVATE_KEY');
        $url =  env('IMAGE_KIT_UPLOAD_URL');
        $dir = env('IMAGE_KIT_DIRECTORY');

        $result = [
            'success' => false,
            'message' => null,
            'data' => null,
            'errors' => null,
        ];

        if (!$file) {
            $result['message'] = 'upload file is empty';
            return $result;
        }


        if ($fileType == 'image' || $fileType == 'images') {
            $folder = 'images';
        } elseif ($fileType == 'video' || $fileType == 'videos') {
            $folder = 'videos';
        } else {
            $folder = 'files';
        }

        $fileExt = $file->getClientOriginalExtension();


        $data =  [
            'auth' => [$secret, ''],
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ],
                [
                    'name' => 'fileName',
                    'contents' => $folder . '_' . now() . '.' . $fileExt,
                ],
                [
                    'name' => 'folder',
                    'contents' => $dir ."/".$folder
                ],
                [
                    'name' => 'tags',
                    'contents' => $folder . ',' . $tags
                ]
            ]
        ];

        try {
            // Initialize Guzzle client
            $client = new Client(['verify' => false]);

            // Prepare the Guzzle request
            $response = $client->post(
                $url,
               $data
            );



            // return $response;
            // return($response->getBody());

            // Decode and print the response
            $response_body = json_decode($response->getBody(), true);

            if(!$response_body){

                $result['message'] = 'Could not upload file';

            }else{
                $result['success'] = true;
                $result['message'] = "File uploaded successfully";
                $result['data'] = $response_body;
            }


        } catch (\Exception $exception) {

            // $exception->getResponse()->getBody(true);
            // return false;
            $result['message'] = 'Server error could not upload file';

        }

        return $result;
    }

    // Delete File
    public function deleteFile($fileId)
    {

        //  private_key//ewq8iwfkdsvckjvcndxcv
        // $privateKey = env('IMG_KIT_PRIVATE_KEY');

        // 'https://api.imagekit.io/v1/files/' . $fileId;
        $deleteUrl = 'https://api.imagekit.io/v1/files/';
        $secret = env('IMAGE_KIT_PRIVATE_KEY');


        if (!$fileId) {
            $result['success'] = false;
            $result['message'] = "File ID is missing";
        }


        // try {
            $client = new Client(['verify' => false]);
            $response = $client->delete($deleteUrl . $fileId, [
                'auth' => [$secret, '']
            ]);

            // Decode response
            // if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            //     $result['success'] = true;
            //     $result['message'] = "Previous file removed successfully";
            // } else {
            //     $result['success'] = true;
            //     $result['message'] = "Error while removing previous file";
            // }
            return $result ?? $response;

        // } catch (\Exception $exception) {

        //     // $exception->getResponse()->getBody(true);
        //     $result['success'] = false;
        //     $result['message'] = "There was an error deleting the resource";
        //     return $result;
        // }
    }

    // Update File
    public function updateFile($fileId)
    {

        //  private_key//ewq8iwfkdsvckjvcndxcv
        $privateKey = env('IMG_KIT_PRIVATE_KEY');
        // 'https://api.imagekit.io/v1/files/' . $fileId . 'details';
        $updateUrl = 'https://api.imagekit.io/v1/files/';

        if (!$fileId) {
            return false;
        }


        try {

            $client = new Client(['verify' => false]);

            $response = $client->patch( $updateUrl . $fileId . '/details', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'auth' => [$privateKey, ''],
                'json' => [
                    'tags' => [
                        'image', '',
                    ],
                ]
            ]);
            // return $result = $response;
            // return $result = $response->getStatusCode();
            return json_decode($response->getBody(), true);

            // Decode response
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $exception) {

            // $exception->getResponse()->getBody(true);
            return false;
        }
    }



    // Not working has curl timeouts
    // public function upload($file, $fileType = 'file', $tags = 'file'){

    //     $secret = env('IMAGE_KIT_PRIVATE_KEY');
    //     $url =  env('IMAGE_KIT_UPLOAD_URL');
    //     $dir = env('IMAGE_KIT_DIRECTORY');

    //     $result = [
    //         'success' => false,
    //         'message' => null,
    //         'data' => null,
    //         'errors' => null,
    //     ];

    //     if (!$file) {
    //         return false;
    //     }

    //     if ($fileType == 'image' || $fileType == 'images') {
    //         $folder = 'images';
    //     } elseif ($fileType == 'video' || $fileType == 'videos') {
    //         $folder = 'videos';
    //     } else {
    //         $folder = 'files';
    //     }

    //     $fileExt = $file->getClientOriginalExtension();

    //     $con = $file->getContent();
    //     $con = base64_encode($con);

    //     $data = [
    //         'multipart' => [
    //             [
    //                 'name' => 'file',
    //                 // 'contents' => fopen($file->getPathname(), 'r'),
    //                 // 'contents' => $file->getPathname(),
    //                 'contents' => $con,
    //                 'filename' => $file->getClientOriginalName(),
    //             ],
    //             // [
    //             //     'name' => 'fileName',
    //             //     'contents' => $folder . '_' . now() . '.' . $fileExt,
    //             // ],
    //             // [
    //             //     'name' => 'folder',
    //             //     'contents' => $dir ."/".$folder
    //             // ],
    //             // [
    //             //     'name' => 'tags',
    //             //     'contents' => $dir . ',' . $tags
    //             // ]
    //         ]
    //     ];

    //     // $data = [
    //         // 'file' => $file,
    //         // 'file' => $file->getRealPath(),
    //         // 'tags' => $tags,
    //         // 'folder' => $dir. "/".$folder,
    //         // 'fileName' => $folder. '_'. now(). '.'. $fileExt,
    //         // 'useFilenameAsPublicID' => true,
    //         // 'overwrite' => true,
    //         // 'metadata' => [
    //         //     'custom_metadata_key' => 'custom_metadata_value'
    //         // ],
    //         // 'conversionOptions' => [
    //         //     'quality' => 80,
    //         //     'width' => 800,
    //         //     'height' => 600
    //         // ]
    //         // 'customCoordinates' => 'x,y,width,height'
    //     // ];

    //     // return $data;

    //     $response = Http::withToken($secret)
    //         // ->withOptions(['verify' => false])
    //         // ->timeout(30)
    //         ->withHeaders([
    //             'Authorization' => 'Bearer '.$secret,
    //             'Content-Type' => 'application/json'
    //         ])->post($url, $data);

    //     // return $response;

    //     if(!$response->successful()){

    //         $result['message'] = 'Could not upload file';

    //     }else{
    //         $result['success'] = true;
    //         $result['message'] = "File uploaded successfully";
    //         $result['data'] = $response->body();
    //     }

    //     return $result;
    // }

}
