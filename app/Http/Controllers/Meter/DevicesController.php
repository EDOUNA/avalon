<?php

namespace App\Http\Controllers\Meter;

use App\Http\Controllers\Controller;
use App\Models\Configurations;
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
        $tariff = DeviceTariffs::with('devices')->whereNull('end_date')->first();
        if (null === $tariff) {
            Log::debug('Could not find an open tariff for deviceID: ' . $deviceID);
            return false;
        }

        return $tariff;
    }

    public function getMeasurements(Int $deviceID, Int $threshold = 10)
    {
        $device = Devices::with('deviceTypes')->where('id', $deviceID)->first();
        if (null === $device) {
            Log::debug('Could not find deviceID: ' . $deviceID);
            return response()->json(['status' => 'No deviceID found.'], 500);
        }

        // Try to find the configuration
        $configuration = Configurations::where('setting', 'domoitcz_meter_live_ui_measurements')->first();
        if (null !== $configuration) {
            $threshold = $configuration->parameter;
        }

        $measurements = DeviceMeasurements::with('deviceTariffs')
            ->where('device_id', $deviceID)
            ->orderBy('created_at', 'desc')
            ->limit($threshold)
            ->get();

        // Set device settings
        $outputMsg['device'] = $device;

        foreach ($measurements as $k => $m) {
            $outputMsg['measurement'][$k]['timestamp'] = $m->created_at;
            $outputMsg['measurement'][$k]['amount'] = round($m->amount, 4);
            $outputMsg['measurement'][$k]['usedEuro'] = round(($m->amount * $m->deviceTariffs->amount), 4);

            // Try to find the next record, to make a comparison
            $outputMsg['measurement'][$k]['usedInComparison'] = null;
            $outputMsg['measurement'][$k]['usedInComparisonEuro'] = null;
            if (isset($measurements[$k + 1])) {
                $outputMsg['measurement'][$k]['usedInComparison'] = round(($m->amount - $measurements[$k + 1]->amount), 4);
                $outputMsg['measurement'][$k]['usedInComparisonEuro'] = round(($outputMsg['measurement'][$k]['usedInComparison'] * $m->deviceTariffs->amount), 4);
            }
        }

        return response()->json($outputMsg);
    }

    /**
     * @param Int $deviceID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActualTariffs(Int $deviceID)
    {
        $device = Devices::where('id', $deviceID)->first();
        if (null === $device) {
            Log::debug('Could not find deviceID: ' . $deviceID);
            return response()->json(['status' => 'No deviceID found.'], 500);
        }

        $tariff = DeviceTariffs::with('currencies')
            ->with(['devices' => function ($devices) use ($deviceID) {
                $devices->where('devices.id', $deviceID)->with('deviceTypes');
            }])
            ->whereNull('end_date')->first();
        if (null === $tariff) {
            Log::debug('Could not find an actual tariff for deviceID: ' . $deviceID);
            return response()->json(['status' => 'Could not find an actual tariff for deviceID: ' . $deviceID], 500);
        }

        return response()->json($tariff);
    }
}
