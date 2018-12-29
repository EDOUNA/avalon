<?php

namespace App\Console\Commands\Meter;

use Illuminate\Console\Command;

class ReadAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avalon:readAPI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start polling the Domoticz API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
