<?php

namespace App\Models\DP;


use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\SupplierVariant;
use App\Observers\ProductVariantObserver;
use App\Scopes\SupplierProductVariantScope;
use App\Traits\PriceableTrait;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use Translatable, PriceableTrait;

    public $translatedAttributes = ['name'];

    public static $searchableColumns = ['id', 'code'];

    protected $appends = ['variantName'];

    protected $hidden = ['position', 'shipping_category_id', 'sold'];

    protected $fillable = [
        'product_id',
        'code',
        'position',
        'width',
        'height',
        'length',
        'weight',
        'stock',
        'shipping_category_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierProductVariantScope());
        static::observe(ProductVariantObserver::class);
    }

    public function scopeFilterSupplier($query, $id)
    {
        $supplier = Supplier::find($id);
        $query->whereIn('id', $supplier->variant_ids);
    }

    public function scopeFilterChannel(Builder $query, $channel)
    {
        $tableName = config('database.connections.dealpaw.database');
        $productIds = DB::table($tableName . '.channel_product')
            ->where('channel_id', $channel)
            ->pluck('product_id');
        $query->whereIn('product_id', $productIds);
    }

    public function scopeWithDpPriceOfChannel(Builder $query, $channel)
    {
        $query->with([
            'dpPrice' => function ($query) use ($channel) {
                $query->filterChannel($channel);
            },
        ]);
    }

    public function appendCurrentPrice($channel)
    {
        if ($channel instanceof Channel) {
            $price = $this->dpPrice()->where('channel_id',$channel->id)->first();
            $this->current_price = optional($price)->price;
            return;
        }
        $this->current_price = 0;
    }

    public function dpPrices()
    {
        return $this->hasMany(ProductVariantPrice::class, 'variant_id');
    }

    public function dpPrice()
    {
        return $this->hasOne(ProductVariantPrice::class, 'variant_id');
    }


    public function getVariantNameAttribute()
    {
        return optional($this->supplierVariant)->name;
    }

    public function getName()
    {
        return optional($this->product)->name . ' ' . implode('-', $this->optionValues->map->name->toArray());
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues()
    {
        return $this->hasMany(ProductVariantOptionValue::class, 'variant_id');
    }


    public function optionValuesGroupByOptionId()
    {
        return optional($this->optionValues, function ($optionValues) {
            return $optionValues->groupBy('option_id');
        });
    }


    public function supplier()
    {
//        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(Supplier::class, 'supplier_variants', 'variant_id', 'supplier_id')
            ->using(SupplierVariant::class);
    }

    public function getSupplierAttribute()
    {
        return $this->supplier();
    }

    public function user()
    {
        return $this->supplierVariant->suplier;
    }

    public function getUserAttribute()
    {
        return $this->user();
    }

    public function supplierVariant()
    {
        return $this->hasOne(SupplierVariant::class, 'variant_id');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'variant_id');
    }

    public function hasPrice()
    {
        return $this->price()->count() > 0;
    }


    public function scopeSearch($query, $search)
    {
        return $query->whereHas('translations', function ($query) use ($search) {
            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $likeOperator = $connectionType == 'pgsql' ? 'ilike' : 'like';
            $query->where('locale_code', app()->getLocale())
                ->where('name', $likeOperator, '%' . $search . '%');
        })->orWhere(function ($query) use ($search) {
            if (is_numeric($search) && in_array($query->getModel()->getKeyType(), ['int', 'integer'])) {
                $query->orWhere($query->getModel()->getQualifiedKeyName(), $search);
            }

            $model = $query->getModel();
            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $likeOperator = $connectionType == 'pgsql' ? 'ilike' : 'like';

            foreach (static::$searchableColumns as $column) {
                $query->orWhere($model->qualifyColumn($column), $likeOperator, '%' . $search . '%');
            }
        });
    }

    protected function incrementStock($pcs)
    {
        $this->increment('stock', $pcs);
    }
}
