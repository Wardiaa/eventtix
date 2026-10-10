<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class KeepAlive extends Command
{
    protected $signature = 'app:keep-alive';
    protected $description = 'Ping the app URL to prevent Render from sleeping';

    public function handle(): int
    {
        $url = config('app.url');

        if (! $url || str_contains($url, 'localhost')) {
            $this->warn('APP_URL is not set to a public URL, skipping ping.');
            return self::SUCCESS;
        }

        try {
            $response = Http::timeout(10)->get($url . '/up');
            $this->info("Pinged {$url}/up — status {$response->status()}");
        } catch (\Throwable $e) {
            $this->error("Ping failed: {$e->getMessage()}");
        }

        return self::SUCCESS;
    }
}