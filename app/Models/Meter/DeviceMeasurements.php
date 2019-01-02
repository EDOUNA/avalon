<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Model;

class DeviceMeasurements extends Model
{
    protected $table = 'device_measurements';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deviceTariffs()
    {
        return $this->hasOne('App\Models\Meter\DeviceTariffs', 'id', 'tariff_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function devices()
    {
        return $this->hasOne('App\Models\Meter\Devices', 'id', 'device_id');
    }
}
