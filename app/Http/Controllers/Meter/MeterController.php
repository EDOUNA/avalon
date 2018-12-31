<?php

namespace App\Http\Controllers\Meter;

use App\Http\Controllers\Controller;
use App\Models\Configurations;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Log;

class MeterController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function liveUI()
    {
        $configuration = Configurations::where('setting', 'domoitcz_meter_live_ui_interval')->first();
        return view('meter.liveui', ['chartInterval' => $configuration->parameter]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDomoticzData()
    {
        $prodURL = Configurations::where('setting', 'domoticz_meter_prod_url')->first();
        if (null === $prodURL) {
            Log::debug('Could not retrieve the prod URL');
            return response()->json(['status' => 'Missing configuration file'], 500);
        }

        $apiUser = Configurations::where('setting', 'domoticz_meter_api_user')->first();
        if (null === $apiUser) {
            Log::debug('Could not retrieve the basic auth user.');
            return response()->json('Missing API user authentication.', 500);
        }

        $client = new Client();
        try {
            $prodResult = $client->request('GET', $prodURL->parameter, ['auth' => $apiUser->parameter]);
        } catch (\Exception $e) {
            Log::debug('Unable to go to: ' . $this->productionURL . ' Extra data: ' . $e->getMessage());
            return response()->json(['status' => 'Response came in too late or contained an error.'], 500);
        }

        $prodResult = json_decode($prodResult->getBody());

        if (!isset($prodResult->result)) {
            Log::debug('Unable to fetch the results array.');
            return response()->json(['status' => 'Could not find results.'], 500);
        }

        $msg = []; // Blank array for later usage
        foreach ($prodResult->result as $r) {
            if (!isset($r->idx)) {
                Log::debug('Unable to find the IDX attribute.');
                return response()->json(['status' => 'Unable to find the IDX attribute.'], 500);
            }

            // Get the device properties
            $device = new DevicesController();
            $deviceObject = $device->findDeviceIDByIDX($r->idx);

            if (false === $device) {
                Log::debug('Unable to find a proper device for IDX: ' . $r->idx);
                return response()->json(['status' => 'Unable to find a proper device for IDX: ' . $r->idx], 500);
            }

            $counter = explode(' ', $r->CounterToday);
            $counter = $counter[0];

            $msg[$deviceObject->deviceTypes->description] = $counter;

            // Create a new measurement log
            $device->createMeasurement($deviceObject->id, $r);
        }

        $time = Carbon::now();
        return response()->json(['status' => 'OK', 'utilities' => $msg, 'serverTime' => $time->toTimeString()]);
    }
}
