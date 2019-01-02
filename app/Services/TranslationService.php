<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2019/1/2
 * Time: 10:25 AM
 */

namespace App\Services;


use Illuminate\Database\Eloquent\Model;

class TranslationService
{
    public static function createTranslation(Model $model, string $attribute, array $data)
    {
//        "name" => [
//            "zh-CN" => "放大法付付付付",
//            "en-US" => "inventory system"
//        ];
//        $germany->translate('en')->name = 'Germany';
//        $germany->save();
        return tap($model, function ($model) use ($attribute, $data) {
            collect($data)->each(function ($value, $locale) use ($model, $attribute) {
                $model->translateOrNew($locale)->{$attribute} = $value;
            });
        });
    }
}