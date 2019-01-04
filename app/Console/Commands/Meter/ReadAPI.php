<?php

namespace App\Console\Commands\Meter;

use App\Http\Controllers\Meter\MeterController;
use App\Models\DeviceMeasurements;
use App\Models\Devices;
use App\Models\DeviceTariffs;
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
        $this->line('Starting to fetch and measure data...');

        $measurement = new MeterController();
        $measurement->getDomoticzData();

        $this->line('All done!');

        return true;
    }
}
