<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function created($data, $description = null)
    {
        $json = [
            'title' => '创建成功',
            'data' => $data,
            'type' => 'success',
        ];
        if ($description) {
            $json = array_merge($json, ['description' => $description]);
        }
        return response()->json($json, 201);
    }
}
