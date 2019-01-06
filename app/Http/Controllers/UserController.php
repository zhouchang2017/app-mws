<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use Illuminate\Http\Request;
use App\Resources\User as UserResource;

class UserController extends Controller
{
    public static $resource = UserResource::class;

    public function notifications(ErpRequest $request)
    {
        return response()->json(
            $request->user()->notifications,
            204
        );
    }
}
