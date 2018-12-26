<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/26
 * Time: 9:57 AM
 */

namespace App\Models\DP;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';
}