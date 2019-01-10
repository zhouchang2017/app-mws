<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/26
 * Time: 9:57 AM
 */

namespace App\Models\DP;

use App\Traits\Authorizable;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use Authorizable;

    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';

    protected $allowAuthorizes = ['update','view','destroy'];

    protected $appendAuthorizes = [];

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function($model){
            $model->append('authorize');
        });
    }

}