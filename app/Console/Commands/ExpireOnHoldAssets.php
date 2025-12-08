<?php

namespace App\Console\Commands;

use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireOnHoldAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:expire-on-hold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire on-hold assets after 3 business days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = Asset::where('status', 'on_hold')
            ->where('on_hold_expires_at' < Carbon::now())
            ->update(['status' => 'available', 'on_hold_expires_at' => null]);

        $this->info("Expired {$expired} old assets");
        return 0;
    }
}
