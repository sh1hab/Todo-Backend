<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait RespondTrait
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @param $message
     * @param $errors
     * @return JsonResponse
     */
    private function respondWithError($message, $errors = '')
    {
        return $this->respond([
            'message' => $message,
            'errors' => $errors
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     * @return JsonResponse
     */
    private function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
