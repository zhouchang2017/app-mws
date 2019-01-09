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