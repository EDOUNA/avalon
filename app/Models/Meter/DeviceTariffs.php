<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Model;

class DeviceTariffs extends Model
{
    protected $table = 'device_tariffs';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devices()
    {
        return $this->belongsTo('App\Models\Meter\Devices', 'tariff_id', 'id');
    }
}
