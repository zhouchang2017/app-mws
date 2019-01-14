<?php

namespace App\Erp;

use Illuminate\Http\Request;
use App\Http\Requests\ErpRequest;

trait ResolvesCards
{
    /**
     * Get the cards that are available for the given request.
     *
     * @param ErpRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function availableCards(ErpRequest $request)
    {
        return $this->resolveCards($request)->filter->authorize($request)->values();
    }

    /**
     * Get the cards for the given request.
     *
     * @param ErpRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function resolveCards(ErpRequest $request)
    {
        return collect(array_values($this->filter($this->cards($request))));
    }

    /**
     * Get the cards available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }
}
