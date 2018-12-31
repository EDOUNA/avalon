<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deviceTypes()
    {
        return $this->hasOne('App\Models\Meter\DeviceTypes', 'id', 'device_type_id');
    }
}
