<?php

namespace App\Meter;

use Illuminate\Database\Eloquent\Model;

class DeviceMeasurementsStats extends Model
{
    protected $table = 'device_measurements_stats';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devices()
    {
        return $this->belongsTo('App\Models\Meter\Devices', 'id', 'device_id');
    }
}
