<?php

namespace Tests\Api\Controllers\UrlController;

use App\Models\UrlMapping;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class WebHashCode extends TestCase
{
    use DatabaseTransactions, WithFaker;

    public function tearDown(): void
    {
        Redis::flushdb();

        parent::tearDown();
    }

    /**
     * 測試短網址重導向
     *
     * @return void
     */
    public function testRedirectUrl()
    {
        // Generate fake data
        UrlMapping::factory()->create(['short_url' => 'ABC']);

        $response = $this->get($this->getUrl());

        $response->assertStatus(301);
    }

    /**
     * 測試短網址重導向 - 短網址不存在
     *
     * @return void
     */
    public function testRedirectUrlWithNotExists()
    {
        $response = $this->get($this->getUrl());

        $response->assertStatus(400)
            ->assertExactJson($this->getFailedJson(['desc' => '網址錯誤']));
    }

    /**
     * 獲取 API URL
     *
     * @return string
     */
    private function getUrl(): string
    {
        return '/ABC';
    }
}
