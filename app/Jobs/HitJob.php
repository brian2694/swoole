<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $endpoint = "http://do.finprof.net/api/counter_mysql";
        $client = new \GuzzleHttp\Client();
        $id = 5;
        $value = "ABC";

        $response = $client->request('GET', $endpoint, ['query' => []]);
        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
        \Log::info("HitJob called");
    }
}
