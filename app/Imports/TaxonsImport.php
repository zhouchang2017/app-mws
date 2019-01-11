<?php

namespace App\Imports;

use App\Models\DP\ProductAttribute;
use App\Models\DP\Taxon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class TaxonsImport implements ToCollection
{

    public $currentTaxon;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        dd($row);
        return new Taxon([
            //
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        DB::connection('dealpaw')->table('taxons')->truncate();
        DB::connection('dealpaw')->table('taxon_translations')->truncate();
        DB::connection('dealpaw')->table('product_attributes')->truncate();
        DB::connection('dealpaw')->table('product_attribute_translations')->truncate();
        $collection->shift();
        $collection->each(function ($item) {
            /** @var Collection $item */
            tap($item->filter()->values(), function ($taxon) {
                /** @var Collection $taxon */
                if ($taxon->count() === 1) {
                    $this->currentTaxon = null;
//                    // 一级分类
                    $this->currentTaxon = $this->createTaxon($taxon->first());

                } else {
                    // 二级
                    $second = $taxon->shift();

                    $this->createAttributes($this->createTaxon($second), $taxon);


                }
            });
        });

    }

    public function createTaxon(string $attr)
    {
        // 0 中文 1 英文
        $attr = explode('/', $attr);
        return tap(new Taxon(), function ($taxon) use ($attr) {
            /** @var Taxon $taxon */
            $taxon->forceFill([
                'code' => $attr[1] . str_random(16),
            ]);
            // create translation
            $this->createTranslations($taxon, $attr);
            $this->createTranslationSlug($taxon, $attr);
            if ( !is_null($this->currentTaxon)) {
                $taxon->parent()->associate($this->currentTaxon);
            }
            $taxon->save();
        });


    }

    public function createTranslations(Model $model, array $data, $attribute = 'name')
    {
        $model->translateOrNew('en-US')->{$attribute} = isset($data[1]) ? $data[1] : '缺少数据';
        $model->translateOrNew('zh-CN')->{$attribute} = isset($data[0]) ? $data[0] : '缺少数据';
    }

    public function createTranslationSlug(Model $model, array $data, $attribute = 'slug')
    {
        $model->translateOrNew('en-US')->{$attribute} = isset($data[1]) ? $this->translation($data[1]) : '缺少数据';
        $model->translateOrNew('zh-CN')->{$attribute} = isset($data[0]) ? $this->translation($data[0]) : '缺少数据';
    }

    public function translation($value)
    {
        return str_slug(app('Faker\Generator')->paragraph);
    }


    public function createAttributes(Taxon $taxon, Collection $attributes)
    {
        $attributes = $attributes->map(function ($attribute) {
            return explode(',', str_replace("\n", ',', trim($attribute)));
        });
        // last to code

        $res = $attributes->reduce(function ($carry, $item) {
            collect($item)->each(function ($attr, $index) use (&$carry) {
                if ( !isset($carry[$index])) {
                    $carry[$index] = [];
                }
                array_push($carry[$index], $attr);
            });
            return $carry;
        }, []);

        collect($res)->each(function ($item) use ($taxon) {
            // 0 中文 1 英文
            tap(new ProductAttribute(), function ($productAttribute) use ($item, $taxon) {

                /** @var ProductAttribute $productAttribute */
                $productAttribute->forceFill([
                    'code' => $item[0] . str_random(16),
                    'type' => 'text',
                    'storage_type' => 'text',
                    'position' => 0,
                ]);
                $productAttribute->taxon()->associate($taxon);
                $this->createTranslations($productAttribute, $item);
                $productAttribute->save();
            });
        });
    }


    public function createAttributeTranslations($productAttribute, array $data)
    {

    }
}
