<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;

class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getSuccessJson($data = []): array
    {
        return [
            'metadata' => [
                'code' => 0,
                'desc' => Arr::get($data, 'desc', 'OK'),
            ],
            'data' => Arr::get($data, 'data'),
        ];
    }

    public function getFailedJson($data = []): array
    {
        return [
            'metadata' => [
                'code' => 1,
                'desc' => Arr::get($data, 'desc', 'OK'),
            ],
            'data' => Arr::get($data, 'data'),
        ];
    }

    public function getValidationErrorJson($data = []): array
    {
        return [
            'metadata' => [
                'code' => 2,
                'desc' => Arr::get($data, 'desc', 'OK'),
            ],
            'data' => Arr::get($data, 'data'),
        ];
    }
}
