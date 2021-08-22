<?php

namespace Tests\Api\Controllers\UrlController;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ApiShortenUrlTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function tearDown(): void
    {
        Redis::flushdb();

        parent::tearDown();
    }

    /**
     * 測試獲取短網址
     *
     * @return void
     */
    public function testShortenUrl()
    {
        $url = $this->faker->url;

        $response = $this->post($this->getUrl(), ['url' => $url]);

        $response->assertStatus(200)
            ->assertExactJson($this->getSuccessJson([
                'data' => [
                    'short_url' => 'http://www.shorten.com/u1N',
                ]
            ]));
    }

    /**
     * 測試獲取短網址 - 錯誤的參數
     *
     * @return void
     *
     * @dataProvider invalidParametersProvider
     */
    public function testShortenUrlInvalidParameters($url, $msg)
    {
        $response = $this->post($this->getUrl(), ['url' => $url]);

        $response->assertStatus(422)
            ->assertExactJson($this->getValidationErrorJson(['desc' => $msg]));
    }

    public function invalidParametersProvider(): array
    {
        return [
            [null, '請輸入網址'],
            ['abc', '網址格式錯誤'],
        ];
    }

    /**
     * 獲取 API URL
     *
     * @return string
     */
    private function getUrl(): string
    {
        return 'api/shorten-url';
    }
}
