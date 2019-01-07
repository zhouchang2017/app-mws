<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2019/1/1
 * Time: 下午4:10
 */

namespace App\Services;


use App\Models\DP\Product;
use App\Models\DP\ProductAttribute;
use App\Models\DP\ProductOption;
use App\Models\DP\ProductVariant;
use App\Models\DP\ProductVariantOptionValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected $product;

    /**
     * ProductService constructor.
     * @param $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }



    public static function updateOrCreateProduct(Request $request, Product $product = null)
    {
        return tap($product ?? new Product(), function ($product) use ($request) {

            /** @var Product $product */
            $product->fill($request->all());

            // save translation field
            collect($request->all())->only($product->translatedAttributes)->each(function ($translation, $attribute) use (
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
                    'locale_code'  => $locale,
                    'text_value'   => $value,
                ]);
            });
        });
    }

    public static function updateOrCreateProductOptions(Product $product, array $options)
    {
        $product->options()->sync($options);
    }

    public function updateOrCreateProductVariant(Request $request, ProductVariant $productVariant = null)
    {
        return DB::transaction(function () use ($request, $productVariant) {
            return tap($productVariant ?? new ProductVariant(), function ($variant) use ($request) {
                /** @var ProductVariant $variant */
                $variant->fill($request->all());
                $variant->product()->associate($this->product);
                TranslationService::createTranslation($variant, 'name', $request->get('name'));
                $variant->save();

                $this->updateOrCreateVariantOption($variant, $request->get('options'));

                $variant->price()->updateOrCreate([
                    'price' => $request->get('price'),
                ]);
            });
        });
    }

    public function updateOrCreateVariantOption(ProductVariant $variant, array $options)
    {
        /*
         * [[
         *    option_id=>1,
         *    value=>[
         *          en-US=>"foo",
         *          zh-CN=>"bar"
         *    ]
         * ...
         * ]]*/
        collect($options)->each(function ($option) use ($variant) {
            $productVariantOptionValue = $variant->optionValues()->updateOrCreate([ 'id' => isset($option['id']) ? $option['id'] : null ], [ 'option_id' => $option['option_id'] ]);
            /** @var ProductVariantOptionValue $productVariantOptionValue */
            TranslationService::createTranslation($productVariantOptionValue, 'value', $option['value']);
            $productVariantOptionValue->save();
        });

    }

    public static function updateOrCreateProductAttribute(Request $request, ProductAttribute $attribute = null)
    {
        return DB::transaction(function () use ($request, $attribute) {
            return tap($attribute ?? new ProductAttribute(), function ($productAttribute) use ($request) {
                /** @var ProductAttribute $productAttribute */
                $productAttribute->fill($request->all());
                TranslationService::createTranslation($productAttribute, 'name', $request->get('name'));
                $productAttribute->save();
            });
        });
    }

    public static function updateOrCreateProductOption(Request $request, ProductOption $option = null)
    {
        return DB::transaction(function () use ($request, $option) {
            return tap($option ?? new ProductOption(), function ($productOption) use ($request) {
                /** @var ProductOption $productOption */
                $productOption->fill($request->all());
                TranslationService::createTranslation($productOption, 'name', $request->get('name'));
                $productOption->save();
            });
        });
    }

}