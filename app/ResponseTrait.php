<?php

namespace App;

use Illuminate\Http\Exceptions\HttpResponseException;

trait ResponseTrait
{

    public function sendSuccess($statusCode = null, $data = null, $message = null, $pagination = null)
    {

        $response = [
            'status' => $statusCode,
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        // Tambahkan `paginate` jika tidak null
        if (!is_null($pagination)) {
            $response['pagination'] = $pagination;
        }

        // Return respons JSON
        return response()->json($response, $statusCode);
    }



    public function SendError($statusCode = null, $error = null, $message = null)
    {

        $response['code'] = $statusCode;
        $response['success'] = false;
        $response['message'] = $message;
        $response['error'] = (!is_null($error)) ? $error : null;

        throw new HttpResponseException(response($response, $statusCode));
    }
}
