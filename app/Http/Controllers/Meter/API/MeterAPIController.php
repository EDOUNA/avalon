<?php

namespace App\Http\Controllers\Meter\API;

use App\Http\Controllers\Controller;
use App\Models\Configurations;
use App\Models\Meter\DeviceMeasurements;
use App\Models\Meter\Devices;
use Log;

class MeterAPIController extends Controller
{
    /**
     * TODO: currently only on day basis. Extend later on to support different enums, or something...
     * @param String $rangeType
     * @return \Illuminate\Http\JsonResponse
     */
    public function budget(String $rangeType)
    {
        $devices = Devices::where('active', 1)->with('deviceTypes', 'deviceTariffs', 'deviceTariffs.currencies')->get()->toArray();
        if (null === $devices) {
            Log::debug('Could not get active devices.');
            return response()->json(['status' => 'Could not retrieve active devices.'], 500);
        }

        $monthlyBudgetConfiguration = Configurations::where('setting', 'energy_monhtly_budget')->first()->parameter;

        $msg = [];
        if ($rangeType == 'd') {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $budgetPerDay = ($monthlyBudgetConfiguration / $daysInMonth);
            $currentDay = date('d');

            $msg['daysRemaining'] = ($daysInMonth - $currentDay);
            $msg['daysPercentage'] = round(100 - ($msg['daysRemaining'] / $daysInMonth) * 100);
            $msg['monthlyBudget'] = round($monthlyBudgetConfiguration, 2);
            $msg['budgetAllowed'] = round($budgetPerDay, 2);
            $msg['budgetSpent'] = 0;
            $msg['budgetCurrency'] = null;

            $measurements = [];
            foreach ($devices as $k => $d) {
                $measurements[$k] = DeviceMeasurements::with('devices', 'deviceTariffs', 'deviceTariffs.currencies')
                    ->where('device_id', $d['id'])
                    ->orderBy('created_at', 'desc')->first();

                if (!isset($msg['budgetCurrency'])) {
                    $msg['budgetCurrency'] = $measurements[$k]['deviceTariffs']['currencies']['symbol'];
                }

                $msg['budgetSpent'] = $msg['budgetSpent'] + ($measurements[$k]['amount'] * $measurements[$k]['deviceTariffs']['amount']);
                $msg['devices'][$k]['description'] = $d['description'];
                $msg['devices'][$k]['amount'] = round(($measurements[$k]['amount'] * $measurements[$k]['deviceTariffs']['amount']), 2);
                $msg['devices'][$k]['currency'] = $measurements[$k]['deviceTariffs']['currencies']['symbol'];
            }

            $msg['budgetSpent'] = round($msg['budgetSpent'], 2);
            $msg['budgetPercentage'] = round(($msg['budgetSpent'] / $msg['budgetAllowed']) * 100);
        }

        $msg['infoBoxClass'] = 'bg-green';
        if ($msg['budgetPercentage'] > 100) {
            $msg['infoBoxClass'] = 'bg-red';
        }

        return response()->json($msg);
    }
}
