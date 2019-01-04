<?php

namespace App\Http\Controllers;

use App\Traits\HttpResource;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The underlying model resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public static $resource;

    public static $indexViewName = 'index';

    /**
     * Build an "index" query for the given resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indexQuery()
    {
        return static::$resource::buildIndexQuery(
            request(),
            static::$resource::newModel()->newQuery(),
            request()->search, $this->getFilters(), $this->orderings()
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $build = $this->indexQuery();
            return response()->json(
                $this->withoutPage() ? $build->get() : $build->paginate()
            );
        }
        $this->viewShare((new static::$resource(static::$resource::newModel()))->authorizedToIndex());
        return view(static::$indexViewName);
    }

    public function getFilters()
    {
        return array_only(request()->all(), static::$resource::$filter);
    }

    public function withoutPage()
    {
        return request()->has('withoutPage');
    }


    /**
     * 视图共享数据
     * @var array
     */
    protected $viewParams = [];

    /**
     * @param array $params
     */
    protected function viewShareParams(array $params = [])
    {
        $this->viewParams = array_merge([
            'uriKey' => static::$resource::uriKey(),
            'label' => static::$resource::label(),
            'singularLabel' => static::$resource::singularLabel(),
        ], $params);
    }


    /**
     * 将数据共享至视图
     * @param array $params
     */
    public function viewShare(array $params = [])
    {
        $this->viewShareParams($params);
        collect($this->viewParams)->each(function ($value, $key) {
            View::share($key, $value);
        });
    }

    /**
     * Get the orderings for the request.
     *
     * @return array
     */
    public function orderings()
    {
        return !empty(request()->orderBy)
            ? [request()->orderBy => request()->orderByDirection ?? 'asc']
            : [];
    }

    /**
     * @param $data
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param null $data
     * @param null $title
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param null $data
     * @param null $title
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleted($data = null, $title = null, $message = null)
    {
        $json = [
            'title' => $title ?? '删除成功',
            'data' => $data,
            'type' => 'success',
        ];
        if ($message) {
            $json = array_merge($json, ['message' => $message]);
        }
        return response()->json($json, 204);
    }

    /**
     * @param $model
     * @return mixed
     */
    public function findRelationship($model)
    {
        return request()->findRelationship($model);
    }

}
