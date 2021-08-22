<?php

namespace App\Providers;

use App\Enums\ResponseCodeEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 成功
        Response::macro('success', function ($data = []) {
            $result = [
                'metadata' => [
                    'code' => Arr::get($data, 'code', ResponseCodeEnum::DEFAULT_SUCCESS),
                    'desc' => Arr::get($data, 'desc', 'OK')
                ],
                'data' => Arr::get($data, 'data', null)
            ];

            return Response::json($result);
        });

        // 失敗
        Response::macro('fail', function ($data = []) {
            $result = [
                'metadata' => [
                    'code' => Arr::get($data, 'code', ResponseCodeEnum::DEFAULT_FAIL),
                    'desc' => Arr::get($data, 'desc', 'ERROR')
                ],
                'data' => Arr::get($data, 'data', null)
            ];

            $statusCode = Arr::get($data, 'statusCode', SymfonyResponse::HTTP_BAD_REQUEST);

            return Response::json($result, $statusCode);
        });
    }
}
