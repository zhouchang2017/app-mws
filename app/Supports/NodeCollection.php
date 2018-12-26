<?php

namespace App\Supports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection as BaseCollection;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;

class NodeCollection extends Collection
{
    /**
     * Fill `parent` and `children` relationships for every node in the collection.
     *
     * This will overwrite any previously set relations.
     *
     * @return $this
     */
    public function linkNodes()
    {
        if ($this->isEmpty()) {
            return $this;
        }

        $groupedNodes = $this->groupBy($this->first()->getParentIdName());

        /** @var NodeTrait|Model $node */
        foreach ($this->items as $node) {
            if ( !$node->getParentId()) {
                $node->setRelation('parent', null);
            }

            $children = $groupedNodes->get($node->getKey(), []);

            /** @var Model|NodeTrait $child */
            foreach ($children as $child) {
                $child->setRelation('parent', $node);
            }
            if (count($children) <= 0) {
                continue;
            }
            $node->setRelation('children', BaseCollection::make($children));
        }

        return $this;
    }
}