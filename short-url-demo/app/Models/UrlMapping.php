<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlMapping extends Model
{
    use HasFactory;

    const SHORT_URL_DOMAIN = 'http://www.shorten.com';

    public $timestamps = false;
    protected $table = 'url_mapping';
    protected $guarded = ['id'];

    public static function getDomain(): string
    {
        return self::SHORT_URL_DOMAIN;
    }
}
