<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Model;

class DeviceMeasurementsStats extends Model
{
    protected $table = 'device_measurements_stats';
    protected $guarded = [];
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function devices()
    {
        return $this->hasOne('App\Models\Meter\Devices', 'id', 'device_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deviceTariffs()
    {
        return $this->hasOne('App\Models\Meter\DeviceTariffs', 'id', 'tariff_id');
    }
}
