<?php

namespace App\Utils;

class ResponseUtil
{
    public function sendResponse($message, $data){
        return [
            'success' => true,
            'message' => $message,
            '$data' => $data
        ];
    }

    public function sendError($code, $message){
        $res = [
            'success' => false,
            'error' => sprintf(config('error_code')[$code], $message),
            'code' => $code
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
