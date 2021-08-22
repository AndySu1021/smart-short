<?php

namespace App\Services;

use App\Facades\Encode;
use App\Models\UrlMapping;
use App\Repositories\UrlMappingRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UrlService
{
    private $urlMappingRepository;

    public function __construct(UrlMappingRepository $urlMappingRepository)
    {
        $this->urlMappingRepository = $urlMappingRepository;
    }

    /**
     * 獲取短網址
     *
     * @param string $longUrl
     * @return string
     */
    public function getShortUrl(string $longUrl): string
    {
        $domain = UrlMapping::getDomain();
        $redisKey = "long:{$longUrl}";

        // Check the redis first if short url key exists
        $cachedLong = Redis::get($redisKey);
        if ($cachedLong) {
            Redis::expire($redisKey, 3600);
            return "{$domain}/{$cachedLong}";
        }

        // Check the database second if long url exists
        $urlMapping = $this->urlMappingRepository->findUrl(
            ['short_url'],
            ['code' => md5($longUrl), 'long_url' => $longUrl]
        );
        if (!empty($urlMapping)) {
            $hashCode = $urlMapping->short_url;
        } else {
            // Get the max id to prevent collision
            $maxId = $this->urlMappingRepository->findUrl([DB::raw('MAX(id) AS id')])->id ?? 4000;
            // Get the encoded id as hash code
            $hashCode = Encode::base62Encode($maxId + 4001);
            // Create url mapping record
            UrlMapping::create([
                'code' => md5($longUrl),
                'long_url' => $longUrl,
                'short_url' => $hashCode,
                'create_time' => Carbon::now()->toDateTimeString(),
            ]);
        }

        Redis::set($redisKey, $hashCode, 'EX', 3600);
        return "{$domain}/{$hashCode}";
    }

    /**
     * 獲取原始網址
     *
     * @param string $hashCode
     * @return string
     */
    public function getLongUrl(string $hashCode): string
    {
        $redisKey = "short:{$hashCode}";

        // Check the redis first if short url key exists
        $cachedShort = Redis::get($redisKey);
        if ($cachedShort) {
            Redis::expire($redisKey, 3600);
            return $cachedShort;
        }

        // find long url from database
        $urlMapping = $this->urlMappingRepository->findUrl(['long_url'], ['short_url' => $hashCode]);
        if ($urlMapping) {
            // Cache short url mapping result to redis
            Redis::set($redisKey, $urlMapping->long_url, 'EX', 3600);
            return $urlMapping->long_url;
        }

        return '';
    }
}
