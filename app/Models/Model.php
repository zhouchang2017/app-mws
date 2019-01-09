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

abstract class Model extends BaseModel
{
    use Authorizable;

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function($model){
            $model->append('authorize');
        });
    }


    public function getAuthorizeAttribute()
    {
        return [
            'canUpdate' => $this->authorizedToUpdate(request()),
            'canView' => $this->authorizedToView(request()),
            'canDestroy' => $this->authorizedToDelete(request()),
        ];
    }
}