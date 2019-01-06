<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Model;

class DeviceMeasurementsStats extends Model
{
    protected $table = 'device_measurements_stats';
    protected $guarded = [];
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devices()
    {
        return $this->belongsTo('App\Models\Meter\Devices', 'id', 'device_id');
    }
}
