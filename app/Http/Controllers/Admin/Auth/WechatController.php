<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Requests\ErpRequest;
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
        $this->middleware('guest')->except(['getBindUrl','checkIsBind']);
    }


    public function bind(User $user, ErpRequest $request)
    {
        $this->authorizeUrl();
        dd($user);
    }
}