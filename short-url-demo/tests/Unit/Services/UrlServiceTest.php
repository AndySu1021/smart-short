<?php

namespace Tests\Unit\Services;

use App\Facades\Encode;
use App\Models\UrlMapping;
use App\Repositories\UrlMappingRepository;
use App\Services\UrlService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    private $urlService;

    public function setUp(): void
    {
        parent::setUp();

        $this->urlService = new UrlService(new UrlMappingRepository());
    }

    public function tearDown(): void
    {
        Redis::flushdb();

        parent::tearDown();
    }

    /**
     * 測試獲取短網址 - Redis 中有快取
     *
     * @return void
     */
    public function testGetShortUrlWithRedisCacheExists()
    {
        $longUrl = $this->faker->url();

        // Set redis data
        Redis::set("long:{$longUrl}", 'ABC');

        // Verify short url
        $shortUrl = $this->urlService->getShortUrl($longUrl);
        $this->assertEquals('http://www.shorten.com/ABC', $shortUrl);
    }

    /**
     * 測試獲取短網址 - 資料庫中有資料
     *
     * @return void
     */
    public function testGetShortUrlWithDatabaseExists()
    {
        // Create fake data
        $urlMapping = UrlMapping::factory()->create();

        // Verify short url
        $shortUrl = $this->urlService->getShortUrl($urlMapping->long_url);
        $this->assertEquals("http://www.shorten.com/{$urlMapping->short_url}", $shortUrl);

        // Verify redis cache
        $cachedLong = Redis::get("long:{$urlMapping->long_url}");
        $this->assertEquals($urlMapping->short_url, $cachedLong);
    }

    /**
     * 測試獲取短網址 - 新資料
     *
     * @return void
     */
    public function testGetShortUrl()
    {
        $longUrl = $this->faker->url();

        // Mock Encode facade
        Encode::shouldReceive('base62encode')->andReturn('ABC');

        // Verify short url
        $shortUrl = $this->urlService->getShortUrl($longUrl);
        $this->assertEquals('http://www.shorten.com/ABC', $shortUrl);

        // Verify if record exists
        $exists = UrlMapping::where('code', md5($longUrl))
            ->where('long_url', $longUrl)
            ->where('short_url', 'ABC')
            ->exists();
        $this->assertTrue($exists);

        // Verify redis cache
        $cachedLong = Redis::get("long:{$longUrl}");
        $this->assertEquals('ABC', $cachedLong);
    }

    /**
     * 測試獲取長網址 - Redis 中有快取
     *
     * @return void
     */
    public function testGetLongUrlWithRedisCacheExists()
    {
        $fakeLongUrl = $this->faker->url();

        // Set redis data
        Redis::set('short:ABC', $fakeLongUrl);

        // Verify long url
        $longUrl = $this->urlService->getLongUrl('ABC');
        $this->assertEquals($fakeLongUrl, $longUrl);
    }

    /**
     * 測試獲取長網址 - 資料庫中有資料
     *
     * @return void
     */
    public function testGetLongUrlWithDatabaseExists()
    {
        // Create fake data
        $urlMapping = UrlMapping::factory()->create();

        // Verify long url
        $longUrl = $this->urlService->getLongUrl($urlMapping->short_url);
        $this->assertEquals($urlMapping->long_url, $longUrl);

        // Verify redis cache
        $cachedShort = Redis::get("short:{$urlMapping->short_url}");
        $this->assertEquals($urlMapping->long_url, $cachedShort);
    }

    /**
     * 測試獲取長網址 - 長網址不存在
     *
     * @return void
     */
    public function testGetLongUrlWithNotExists()
    {
        // Verify long url
        $longUrl = $this->urlService->getLongUrl('ABC');
        $this->assertEquals('', $longUrl);

        // Verify redis cache
        $cachedShort = Redis::get("short:ABC");
        $this->assertNull($cachedShort);
    }
}
