<?php

namespace App\Http\Controllers\Meter;

use App\Http\Controllers\Controller;
use App\Models\Meter\Devices;

class DevicesController extends Controller
{
    /**
     * @param Int $idx
     * @return object|bool
     */
    public function findDeviceIDByIDX(Int $idx)
    {
        $device = Devices::with('deviceTypes')->where('identifier', $idx)->first();
        if (null === $device) {
            return false;
        }

        return $device;
    }

    public function createMeasurement(Int $deviceID, Array $jsonResponse)
    {

    }
}
