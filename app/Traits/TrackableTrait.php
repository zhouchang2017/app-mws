<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 3:54 PM
 */

namespace App\Traits;


use App\Models\ShipmentTrack;

trait TrackableTrait
{

    public function tracks()
    {
        return $this->morphMany(ShipmentTrack::class, 'trackable');
    }

    public function track()
    {
        return $this->morphOne(ShipmentTrack::class, 'trackable');
    }

    public function hasTracks()
    {
        return $this->tracks()->count() > 0;
    }

    public function isShipped(): bool
    {

    }

}