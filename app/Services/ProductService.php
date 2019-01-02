<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2019/1/1
 * Time: 下午4:10
 */

namespace App\Services;


use App\Models\DP\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductService
{
    public static function updateOrCreateProduct(Request $request, Product $product = null)
    {
        return tap($product ?? new Product(), function ($product) use ($request) {

            /** @var Product $product */
            $product->fill($request->all());

            // save translation field
            collect($request->all())->only($product->translatedAttributes)->each(function ($translation, $attribute) use
            (
                $product
            ) {

                TranslationService::createTranslation($product, $attribute, $translation);
            });

            $product->save();

            if ($request->has('attributes')) {
                static::updateOrCreateProductAttributeValue($product, $request->input('attributes'));
            }

            if ($request->has('options')) {
                static::updateOrCreateProductOptions($product, $request->get('options'));
            }
        });
    }

    public static function updateOrCreateProductAttributeValue(Product $product, array $attributes)
    {
        collect($attributes)->each(function ($attribute) use ($product) {
            collect($attribute['value']['value'])->each(function ($value, $locale) use ($product, $attribute) {
                $product->attributeValues()->updateOrCreate([
                    'id' => array_get($attribute, 'value.id'),
                ], [
                    'attribute_id' => $attribute['attribute_id'],
                    'locale_code' => $locale,
                    'text_value' => $value,
                ]);
            });
        });
    }

    public static function updateOrCreateProductOptions(Product $product, array $options)
    {
        $product->options()->sync($options);
    }
}