<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @var string
     */
    protected $status = 'success';

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
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

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondCreated($message = 'Resource Created')
    {
        return $this->setStatusCode(201)
            ->respondMessage($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondData($response)
    {
        return $this->respond([
            'status' => $this->getStatus(),
            'status_code' => $this->getStatusCode(),
            'data' => $response,
        ]);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondMessage($message = '')
    {
        return $this->respond([
            'status' => $this->getStatus(),
            'status_code' => $this->getStatusCode(),
            'message' => $message,
        ]);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondWithError($message = '')
    {
        return $this->setStatus('error')
            ->respondMessage($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)
            ->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)
            ->respondWithError($message);
    }
}