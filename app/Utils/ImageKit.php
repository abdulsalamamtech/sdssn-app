<?php

namespace App\Libraries;

use GuzzleHttp\Client;

class Imagekit
{

    // Upload File
    public function uploadFile($file, $fileType = 'file', $tags = 'file')
    {

        //  private_key//ewq8iwfkdsvckjvcndxcv
        $privateKey = env('IMG_KIT_PRIVATE_KEY');
        // 'https://upload.imagekit.io/api/v1/files/upload';
        $uploadUrl = env('IMG_KIT_UPLOAD_URL');

        if (!$file) {
            return false;
        }

        if ($fileType == 'image' || $fileType == 'images') {
            $folder = 'images';
        } elseif ($fileType == 'video' || $fileType == 'videos') {
            $folder = 'videos';
        } else {
            $folder = 'files';
        }

        $fileExt = $file->getClientOriginalExtension();

        try {
            // Initialize Guzzle client
            $client = new Client(['verify' => false]);

            // Prepare the Guzzle request
            $response = $client->post(
                $uploadUrl,
                [
                    'auth' => [$privateKey, ''],
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
                            'contents' => $folder
                        ],
                        [
                            'name' => 'tags',
                            'contents' => $folder . ',' . $tags
                        ]
                    ]
                ]
            );

            // Decode and print the response
            return json_decode($response->getBody(), true);
        } catch (\Exception $exception) {

            // $exception->getResponse()->getBody(true);
            return false;
        }
    }

    // Delete File
    public function deleteFile($fileId)
    {

        //  private_key//ewq8iwfkdsvckjvcndxcv
        $privateKey = env('IMG_KIT_PRIVATE_KEY');
        // 'https://api.imagekit.io/v1/files/' . $fileId;
        $deleteUrl = env('IMG_KIT_DELETE_URL');

        if (!$fileId) {
            return false;
        }


        try {
            $client = new Client(['verify' => false]);
            $response = $client->delete($deleteUrl . $fileId, [
                'auth' => [$privateKey, '']
            ]);

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

}
