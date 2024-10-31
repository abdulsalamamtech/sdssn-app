<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

trait ApiResponseTrait
{
    public function sendSuccess($data = [], $message = 'successful', $statusCode = 200, $metadata = null)    {
        // Return response
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'metadata' => $metadata,
        ], $statusCode);
    }

    public function sendError($error = [], $message = 'there was an error', $statusCode = 500)    {
        // Return response
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $error,
        ], $statusCode ?? 500);
    }

    public function getMetadata($data){

        if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
            return [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'previous_page_url' => $data->previousPageUrl(),
                'next_page_url' => $data->nextPageUrl(),
                'pages' => $data->getUrlRange(1, $data->lastPage())
            ];
        }

        return null;
    }
}
