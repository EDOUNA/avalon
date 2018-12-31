<?php

namespace App\Http\Controllers\Meter;

use App\Http\Controllers\Controller;
use App\Models\Meter\DeviceMeasurements;
use App\Models\Meter\Devices;
use App\Models\Meter\DeviceTariffs;
use Log;

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

    /**
     * @param Int $deviceID
     * @param Object $jsonResponse
     * @return bool
     */
    public function createMeasurement(Int $deviceID, Object $jsonResponse)
    {
        $device = Devices::where('id', $deviceID)->first();
        if (null === $device) {
            Log::debug('Could not find device with ID: ' . $deviceID);
            return false;
        }

        $counter = explode(' ', $jsonResponse->CounterToday);
        $counter = $counter[0];

        // Find a matching tariff
        $tariff = $this->findTariffByDeviceID($deviceID);

        $newMeasurement = new DeviceMeasurements;
        $newMeasurement->amount = $counter;
        $newMeasurement->device_id = $deviceID;
        $newMeasurement->tariff_id = $tariff->id;
        $newMeasurement->json_serialize = json_encode($jsonResponse);

        $newMeasurement->save();

        return true;
    }

    /**
     * @param Int $deviceID
     * @return bool|object
     */
    public function findTariffByDeviceID(Int $deviceID)
    {
        $tariff = DeviceTariffs::where(['device_id' => $deviceID, 'end_date' => null])->first();
        if (null === $tariff) {
            Log::debug('Could not find an open tariff for deviceID: ' . $deviceID);
            return false;
        }

        return $tariff;
    }
}
