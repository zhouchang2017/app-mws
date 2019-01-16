<?php

namespace App\Http\Controllers\Supplier\Auth;

use App\Http\Requests\ErpRequest;
use App\Models\SupplierUser;
use App\Models\User;
use App\Traits\WechatBindTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    use WechatBindTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['getBindUrl', 'checkIsBind']);
        $this->middleware('wechat.oauth')->only(['doBind']);
    }

    public function doBind(SupplierUser $user, ErpRequest $request)
    {
        if ( !$user->hasBind()) {
            /** @var \Overtrue\Socialite\User $oauthUser */
            $oauthUser = session('wechat.oauth_user.default');
            $user->wechat()->create([
                'openid' => $oauthUser->getId(),
                'avatar' => $oauthUser->getAvatar() ?? 'default',
                'nickname' => $oauthUser->getNickname() ?? str_random(16),
            ]);
            return view('wechat.success', ['message' => '绑定成功！']);
        }
        return view('wechat.success', ['message' => '您已经绑定，无需再次绑定！']);
    }


    public function bind(SupplierUser $user, ErpRequest $request)
    {
        $this->authorizeUrl();
        return $this->doBind($user, $request);
    }
}
