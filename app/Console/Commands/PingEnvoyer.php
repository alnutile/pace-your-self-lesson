<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PingEnvoyer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heartbeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer Heartbeat';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Http::get('http://beats.envoyer.io/heartbeat/'.env('ENVOYER_PING'));
    }
}
