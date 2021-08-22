<?php

namespace App\Libraries;

class Encode
{
    /**
     * 產生短網址 base 10 -> base 62
     *
     * @param int $id
     * @return string
     */
    public function base62Encode(int $id): string
    {
        $alphabet = 'yEuNb1H9rUmqsQZT2G6jVPJz8nOt0doRaD3w4KxSkvcielpMXhALWfg5IYFC7B';
        $result = '';

        while ($id) {
            $index = $id % strlen($alphabet);
            $id = (int)($id / strlen($alphabet));
            $result .= $alphabet[$index];
        }

        return strrev($result);
    }
}
