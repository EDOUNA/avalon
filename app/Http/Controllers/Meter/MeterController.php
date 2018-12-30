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

        $msg = [];
        foreach ($prodResult->result as $r) {
            $counter = explode(' ', $r->CounterToday);
            $counter = $counter[0];
            if ($r->idx == 2) {
                $msg['energy'] = $counter;
                continue;
            } elseif ($r->idx == 6) {
                $msg['gas'] = $counter;
                continue;
            }
            Log::debug('Unknown IDX pattern.');
            return response()->json(['status' => 'Application should not end up here.'], 500);
        }

        $time = Carbon::now();
        return response()->json(['status' => 'OK', 'utilities' => $msg, 'serverTime' => $time->toTimeString()]);
    }
}
