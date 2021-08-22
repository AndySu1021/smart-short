<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortenUrlRequest;
use App\Services\UrlService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class UrlController extends Controller
{
    private $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    /**
     * 獲取短網址
     *
     * @param ShortenUrlRequest $request
     * @return mixed
     */
    public function shortenUrl(ShortenUrlRequest $request)
    {
        $params = $request->validated();

        $shortUrl = $this->urlService->getShortUrl($params['url']);

        return response()->success(['data' => ['short_url' => $shortUrl]]);
    }

    /**
     * 短網址重導向
     *
     * @param $hashCode
     * @return Application|RedirectResponse|Redirector
     */
    public function redirectUrl($hashCode)
    {
        $longUrl = $this->urlService->getLongUrl($hashCode);
        if (empty($longUrl)) {
            return response()->fail(['desc' => '網址錯誤']);
        }

        return redirect($longUrl, 301);
    }
}
