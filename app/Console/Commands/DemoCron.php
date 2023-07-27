<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Http\Controllers\EventController;
class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $event = new \App\Http\Controllers\EventController();
            $event->cron();
        \Log::info("Cron is working fine!");
    //    mail('haroonafzaal03@gmail.com','hi');
         $this->info('Demo:Cron Cummand Run successfully!');
    }
}
