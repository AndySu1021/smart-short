<?php

namespace App\Repositories;

use App\Models\UrlMapping;
use Illuminate\Support\Arr;

class UrlMappingRepository
{
    public function findUrl(array $selects = ['*'], array $conditions = [])
    {
        $urlMapping = UrlMapping::select($selects);

        if (isset($conditions['code'])) {
            $urlMapping->where('code', Arr::get($conditions, 'code'));
        }

        if (isset($conditions['short_url'])) {
            $urlMapping->where('short_url', Arr::get($conditions, 'short_url'));
        }

        if (isset($conditions['long_url'])) {
            $urlMapping->where('long_url', Arr::get($conditions, 'long_url'));
        }

        return $urlMapping->first();
    }
}
