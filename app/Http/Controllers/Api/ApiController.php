<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;

class ApiController extends Controller
{
    use RespondTrait;

    /**
     * @param string $message
     * @param string $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotValidated($message = '', $errors = '')
    {
        $message = $message === '' ? config('response.status.not_validated') : $message;
        return $this->setStatusCode(400)->respondWithError($message, $errors);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotAuthorized($message = '')
    {
        $message = $message === '' ? config('response.status.not_authorized') : $message;
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = '', $status = 200)
    {
        $message = $message === '' ? config('response.status.not_found') : $message;
        return $this->setStatusCode($status)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = '')
    {
        $message = $message === '' ? config('response.status.internal_error') : $message;
        return $this->setStatusCode(500)->respondWithError($message);
    }

    public function respondForbidden($message = '', $errors = '')
    {
        $message = $message === '' ? config('response.status.forbidden') : $message;
        return $this->setStatusCode(403)->respondWithError($message, $errors);
    }

    public function respondBadGateway($message = '', $errors = '')
    {
        $message = $message === '' ? config('response.status.bad_gateway') : $message;
        return $this->setStatusCode(502)->respondWithError($message, $errors);
    }
}
