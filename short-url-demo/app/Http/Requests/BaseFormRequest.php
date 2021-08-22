<?php

namespace App\Http\Requests;

use App\Enums\ResponseCodeEnum;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

abstract class BaseFormRequest extends LaravelFormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $error = array_values($errors)[0][0];

        throw new HttpResponseException(response()->fail([
            'desc' => $error,
            'code' => ResponseCodeEnum::DEFAULT_VALIDATION_ERROR,
            'statusCode' => SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
        ]));
    }
}
