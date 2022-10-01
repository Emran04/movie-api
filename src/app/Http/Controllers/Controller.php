<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Response errors as JSON.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseValidatorJson(Validator $validator): JsonResponse
    {
        return new JsonResponse([
            'status'  => 'error',
            'message' => 'Data validation error',
            'errors'  => $validator->errors()->toArray(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
