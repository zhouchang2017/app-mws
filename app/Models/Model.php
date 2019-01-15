<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/9
 * Time: 3:04 PM
 */

namespace App\Models;

use App\Traits\Authorizable;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

abstract class Model extends BaseModel
{
    use Authorizable;

    protected $allowAuthorizes = ['update','view','destroy'];

    protected $appendAuthorizes = [];

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function($model){
            $model->append('authorize');
        });
    }

    public function freshNow()
    {
        $this->forceFill(['updated_at' => $this->freshTimestamp()])->save();
    }
}