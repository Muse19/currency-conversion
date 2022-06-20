<?php


namespace App\Traits;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json(['data' => $data, 'status' => $code], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['message' => $message, 'status' => $code], $code);
    }

    protected function showResponse($data, $code = 200)
    {
        return $this->successResponse($data, $code);
    }
}
