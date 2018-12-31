<?php

namespace App\Console\Commands\Meter;

use App\Models\Configurations;
use App\Models\DeviceMeasurements;
use App\Models\Devices;
use App\Models\DeviceTariffs;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Log;

class ReadAPI extends Command
{
    /**
     * @var string
     */
    protected $signature = 'avalon:readAPI';

    /**
     * @var string
     */
    protected $description = 'Start polling the Domoticz API';

    /**
     * @var
     */
    protected $productionURL;

    /**
     * @var
     */
    protected $testURL;

    /**
     * @var array
     */
    protected $apiUser = [];

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): bool
    {
        // Load initial configuration
        if (!$this->initConfiguration()) {
            $this->error('Something went wrong in initializing the configuration.');
            return false;
        }

        $client = new Client();
        try {
            $testResult = $client->request('GET', $this->testURL);
        } catch (\Exception $e) {
            Log::debug('Unable to go to: ' . $this->testURL . ' Extra data: ' . $e->getMessage());
            $this->error('Unable to reach the test URL.');
            return false;
        }

        $testResult = json_decode($testResult->getBody());
        if (!isset($testResult->status) || $testResult->status !== "OK") {
            Log::debug('Domoticz reported an invalid status back. Stopping sequence.');
            $this->error('Invalid response.');
            return false;
        }

        unset($client);
        $client = new Client();
        try {
            $prodResult = $client->request('GET', $this->productionURL, ['auth' => $this->apiUser]);
        } catch (\Exception $e) {
            Log::debug('Unable to go to: ' . $this->productionURL . ' Extra data: ' . $e->getMessage());
            $this->error('Unable to reach the production URL.');
            return false;
        }

        $prodResult = json_decode($prodResult->getBody());
        if (!isset($prodResult->status) || $prodResult->status !== "OK" || !isset($prodResult->result)) {
            Log::debug('Domoticz reported an invalid status back. Stopping sequence.');
            $this->error('Invalid response.');
            return false;
        }

        // Try to find the matching device
        foreach ($prodResult->result as $k => $d) {
            $device = Devices::where('identifier', $d->idx)->first();
            $tariff = DeviceTariffs::where(['device_id' => $device->id, 'end_date' => null])->first();

            $counter = explode(' ', $d->CounterToday);

            $newMeasurement = new DeviceMeasurements;
            $newMeasurement->device_id = $device->id;
            $newMeasurement->tariff_id = $tariff->id;
            $newMeasurement->amount = $counter[0];
            $newMeasurement->json_serialize = json_encode($d);
            $newMeasurement->save();
        }
        
        return true;
    }

    /**
     * @return bool
     */
    private function initConfiguration(): bool
    {
        $testURL = Configurations::where('setting', 'domoticz_meter_test_url')->first();
        if (null === $testURL) {
            Log::debug('Could not retrieve the test URL.');
            return false;
        }

        $this->line('Test URl set to: ' . $testURL->parameter);
        $this->testURL = $testURL->parameter;

        $prodURL = Configurations::where('setting', 'domoticz_meter_prod_url')->first();
        if (null === $prodURL) {
            Log::debug('Could not retrieve the prod URL');
            return false;
        }

        $this->line('Production URL set to: ' . $prodURL->parameter);
        $this->productionURL = $prodURL->parameter;

        $apiUser = Configurations::where('setting', 'domoticz_meter_api_user')->first();
        if (null === $apiUser) {
            Log::debug('Could not retrieve the basic auth user.');
            return false;
        }

        $this->line('API user set.');
        $this->apiUser = explode(',', $apiUser->parameter);

        return true;
    }
}
