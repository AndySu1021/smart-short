<?php

namespace Tests\Unit\Models;

use App\Models\UrlMapping;
use Tests\TestCase;

class UrlMappingTest extends TestCase
{
    /**
     * 測試獲取域名
     *
     * @return void
     */
    public function testGetDomain()
    {
        $expected = 'http://www.shorten.com';
        $domain = UrlMapping::getDomain();
        $this->assertEquals($expected, $domain);
    }
}
