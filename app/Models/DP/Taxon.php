<?php

namespace App\Models\DP;


use App\Supports\NodeCollection;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Taxon extends Model
{
    use Translatable, NodeTrait, SoftDeletes;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'code',
        'parent_id',
        'position',
        'tree_root',
        'image',
        'tree_level',
        'tree_left',
        'tree_right',
    ];


    public function getLftName()
    {
        return 'tree_left';
    }

    public function getRgtName()
    {
        return 'tree_right';
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }

    public function scopeChildren($query, $treeLeft, $treeRight)
    {
        return $query->where('tree_left', '>', $treeLeft)->where('tree_right', '<', $treeRight);
    }

    public function hasChildren()
    {
        return (bool)$this->children()->count();
    }

    public function getParentNameAttribute()
    {
        return $this->parent->name ?? null;
    }

    public function newCollection(array $models = [])
    {
        return new NodeCollection($models);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
}
