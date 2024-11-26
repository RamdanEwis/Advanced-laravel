<?php

namespace App\Helpers;

use App\Contracts\Status_Responses;

trait ApiResponse
{
    public function jsonResponse($payLoad)
    {
        return response()->json([
            'status_code' => $payLoad['statusCode'],
            'message'     => $payLoad['message'],
            'data'        => $payLoad['data'],
        ], $payLoad['statusCode']);
    }

    public function jsonPayLoad($statusCode, $message, $data = [], $ok = true)
    {
        $payLoad = [
            'statusCode' => $statusCode,
            'ok'         => $ok,
            'message'    => $message,
            'data'       => $data,
        ];

        return $this->jsonResponse($payLoad);
    }

    public function response_msg($status_code)
    {
        return Status_Responses::get_response_msg($status_code);
    }

    public function forbidden_response($msg = null)
    {
        $msg = $msg ?? $this->response_msg(Status_Responses::FORBIDDEN);
        return $this->jsonPayLoad(Status_Responses::FORBIDDEN, $msg);
    }

    public function created_response($data = [], $msg = null)
    {
        $msg = $msg ?? $this->response_msg(Status_Responses::CREATED);
        return $this->jsonPayLoad(Status_Responses::CREATED, $msg, $data);
    }

    public function success_response($data = [], $msg = null)
    {
        $msg = $msg ?? $this->response_msg(Status_Responses::OK);
        return $this->jsonPayLoad(Status_Responses::OK, $msg, $data);
    }

    public function ok_response($data = [], $msg = null)
    {
        $msg = $msg ?? $this->response_msg(Status_Responses::OK);
        return $this->jsonPayLoad(Status_Responses::OK, $msg, $data);
    }

    public function ok_response_pagination($data, $pagination, $totalItems)
    {
        $msg = $this->response_msg(Status_Responses::OK);
        return response()->json([
            'status_code'  => Status_Responses::OK,
            'message'      => $msg,
            'data'         => $data,
            'total_items'  => $totalItems,
            'pagination'   => $this->generatePaginationLinks($pagination),
        ]);
    }

    public function unauthorized_response($msg = null)
    {
        $msg = $msg ?? $this->response_msg(Status_Responses::UNAUTHORIZED);
        return $this->jsonPayLoad(Status_Responses::UNAUTHORIZED, $msg);
    }

    public function unprocessable_response($errors = [])
    {
        return $this->jsonPayLoad(
            Status_Responses::UNPROCESSABLE_ENTITY,
            $this->response_msg(Status_Responses::UNPROCESSABLE_ENTITY),
            $errors
        );
    }

    public function not_found_response()
    {
        return $this->jsonPayLoad(
            Status_Responses::NOT_FOUND,
            $this->response_msg(Status_Responses::NOT_FOUND)
        );
    }

    public function not_found_response_msg($msg)
    {
        return $this->jsonPayLoad(Status_Responses::NOT_FOUND, $msg);
    }

    public function internal_server_error_response()
    {
        return $this->jsonPayLoad(
            Status_Responses::INTERNAL_SERVER_ERROR,
            $this->response_msg(Status_Responses::INTERNAL_SERVER_ERROR)
        );
    }

    public function bad_request($errors = [], $msg = null)
    {
        $msg = $msg ?? $this->response_msg(Status_Responses::BAD_REQUEST);
        return $this->jsonPayLoad(Status_Responses::BAD_REQUEST, $msg, $errors);
    }


    public function generatePaginationLinks($pagination)
    {
        return [
            'current_page' => $pagination->currentPage(),
            'last_page'    => $pagination->lastPage(),
            'per_page'     => $pagination->perPage(),
            'total'        => $pagination->total(),
            'links'        => [
                'next'     => $pagination->nextPageUrl(),
                'previous' => $pagination->previousPageUrl(),
            ],
        ];
    }
}
