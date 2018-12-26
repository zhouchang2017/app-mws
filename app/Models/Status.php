<?php

namespace App\Models;

use Spatie\ModelStatus\Status as BaseStatus;

class Status extends BaseStatus
{
    protected static function boot()
    {
        parent::boot();
        static::created(function ($status) {
            if (auth()->check()) {
                $status->user()->associate(auth()->user())->save();
            }
        });
    }


    public function user()
    {
        return $this->morphTo();
    }
}