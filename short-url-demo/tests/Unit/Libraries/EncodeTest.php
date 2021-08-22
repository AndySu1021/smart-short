<?php

namespace Tests\Unit\Libraries;

use App\Libraries\Encode;
use Tests\TestCase;

class EncodeTest extends TestCase
{
    /**
     * 測試 base 10 -> base 62
     *
     * @return void
     */
    public function testBase62Encode()
    {
        $encode = new Encode();
        $result = $encode->base62Encode(4000);
        $this->assertEquals('Eua', $result);
    }
}
