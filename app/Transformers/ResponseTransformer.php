<?php

namespace App\Transformers;

class ResponseTransformer
{

    public static function response(bool $status = false, string $title, string $message = null, array $data = [], int $response_code = null)
    {
        $response_code = !$response_code ? ($status ? 200 : 500) : $response_code;
        $message = !$message ? ($status === 200 ? "Request successfully processed" : "Unable to process your request due to server issue. Please try later.") : $message;
        $data = ($response_code === 500) ? ['Internal server error'] : $data;

        $data = count($data) ? $data : (Object) ($data);

        if ($status) {
            return response()->json(
                [
                    'status' => 'success',
                    'title' => $title,
                    'message' => $message,
                    'data' => $data
                ],
                $response_code
            );
        }

        // send error as header does not have any device id
        return response()->json(
            [
                'status' => 'fail',
                'title' => $title,
                'message' => $message,
                'errors' => $data
            ],
            $response_code
        );
    }
}