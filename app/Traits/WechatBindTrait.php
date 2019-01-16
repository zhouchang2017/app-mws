<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/11
 * Time: 2:32 PM
 */

namespace App\Traits;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

/**
 * Trait WechatBindTrait
 * @package App\Traits
 */
trait WechatBindTrait
{
    /**
     * 有效时间
     * @return Carbon
     */
    public function expires()
    {
        return now()->addMinutes(5);
    }

    /**
     * 缓存url
     * @param $url
     * @param null $expires
     */
    public function cacheUrl($url, $expires = null)
    {
        Cache::put($url, $url, $expires ?? $this->expires());
    }

    /**
     * 验证url有效性
     */
    public function authorizeUrl()
    {
        dump(Cache::has(request()->fullUrl()));
        dump(request()->hasValidSignature());
        dd(1);
        tap(Cache::has(request()->fullUrl()), function ($flag) {
            throw_unless($flag && request()->hasValidSignature(), AuthorizationException::class);
            Cache::forget(request()->fullUrl());
        });
    }

    /**
     * @return string
     */
    public function getBindUrl()
    {
        return response()->json(
            tap(URL::temporarySignedRoute(
                erpRequest()->getSubDomain() . '.wechat.bind',
                $this->expires(),
                ['user' => auth()->user()]), function ($url) {
                $this->cacheUrl($url);
            })
        );
    }

    public function checkIsBind()
    {
        return response()->json(
            auth()->user()->hasBind()
        );
    }

}