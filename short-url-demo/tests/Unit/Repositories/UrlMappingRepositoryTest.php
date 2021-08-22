<?php

namespace Tests\Unit\Repositories;

use App\Models\UrlMapping;
use App\Repositories\UrlMappingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UrlMappingRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 測試搜尋 Url
     *
     * @return void
     */
    public function testFindUrl()
    {
        $urlMappingRepository = new UrlMappingRepository();

        // Generate fake data
        $urlMapping = UrlMapping::factory()->create();

        $conditions = [
            'long_url' => $urlMapping->long_url,
            'short_url' =>$urlMapping->short_url,
        ];

        $result = $urlMappingRepository->findUrl(['long_url', 'short_url'], $conditions);

        $this->assertEquals($urlMapping->long_url, $result->long_url);
        $this->assertEquals($urlMapping->short_url, $result->short_url);
    }
}
