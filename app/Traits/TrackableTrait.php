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
        return $this->morphToMany(ShipmentTrack::class, 'trackable');
    }

    public function track()
    {
        return $this->morphToMany(ShipmentTrack::class, 'trackable');
    }

    public function hasTracks()
    {
        return $this->tracks()->count() > 0;
    }

    public function getIsShippedAttribute()
    {
        return $this->hasTracks() ? '已发货' : '待发货';
    }

    public function shipment($data)
    {
        if ($data instanceof ShipmentTrack) {
            $shipment = $data;
        } else {
            $shipment = ShipmentTrack::firstOrNew($data);
            $shipment->save();
        }

        if ( !$this->tracks()->where('shipment_track_id', $shipment->id)->first()) {
            $this->tracks()->attach($shipment);
        }
        return $shipment;
    }
}