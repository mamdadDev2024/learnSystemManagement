<?php

namespace App\Contracts;

use App\Contracts\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
    throw new HttpResponseException(
            ApiResponse::validation($validator->errors())
        );
    }

    public function unauthenticated(){
        throw new HttpResponseException(
            response()->json(["errors"=> ['message' => "Unauthenticated." , 'status' => 'error']] , 422)
        );
    }
}
