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

    public function shipment($data)
    {
        if (is_array($data)) {
            $data = ShipmentTrack::firstOrNew($data);
        }
        if ( !$this->tracks()->where('shipment_track_id', $data->id)->count() > 0) {
            $this->tracks()->attach($data);
        }
        return $data;
    }
}