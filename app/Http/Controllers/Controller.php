<?php

namespace App\Http\Controllers;

use App\Traits\HttpResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, HttpResource;


    public function __construct()
    {
        View::share('uriKey', $this->getUriKey());
        View::share('label', $this->getLabel());
        View::share('singularLabel', $this->getSingularLabel());
    }

    public function created($data, $message = null)
    {
        $json = [
            'title' => '创建成功',
            'data'  => $data,
            'type'  => 'success',
        ];
        if ($message) {
            $json = array_merge($json, [ 'message' => $message ]);
        }
        return response()->json($json, 201);
    }

    public function updated($data = null, $title = null, $message = null)
    {
        $json = [
            'title' => $title ?? '更新成功',
            'data'  => $data,
            'type'  => 'success',
        ];
        if ($message) {
            $json = array_merge($json, [ 'message' => $message ]);
        }
        return response()->json($json, 200);
    }

    public function deleted($data = null, $title = null, $message = null)
    {
        $json = [
            'title' => $title ?? '删除成功',
            'data'  => $data,
            'type'  => 'success',
        ];
        if ($message) {
            $json = array_merge($json, [ 'message' => $message ]);
        }
        return response()->json($json, 204);
    }

    public function findRelationship($model)
    {
        return request()->findRelationship($model);
    }

}
