<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Model;

class DeviceTypes extends Model
{
    protected $table = 'device_types';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devices()
    {
        return $this->belongsTo('App\Models\Meter\Devices', 'device_type_id', 'id');
    }
}
