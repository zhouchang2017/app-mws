<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function created($data, $message = null)
    {
        $json = [
            'title' => '创建成功',
            'data' => $data,
            'type' => 'success',
        ];
        if ($message) {
            $json = array_merge($json, ['message' => $message]);
        }
        return response()->json($json, 201);
    }

    public function updated($data = null, $title = null, $message = null)
    {
        $json = [
            'title' => $title ?? '更新成功',
            'data' => $data,
            'type' => 'success',
        ];
        if ($message) {
            $json = array_merge($json, ['message' => $message]);
        }
        return response()->json($json, 200);
    }
}
