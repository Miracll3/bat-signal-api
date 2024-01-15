<?php

namespace App\Actions;


class JsonResponseFormat
{
    public static function handle($data = [], $message ='Action completed successfully', $status = 200)
    {
        $statusDescription = 'success';
        if($status != 200){
            $statusDescription = 'error';
        }

        $response = [
            'status' => $statusDescription,
            'message' => $message,
            'data' => empty($data) ? (object)[] : $data,
        ];

        return response()->json($response, $status);
    }
}
