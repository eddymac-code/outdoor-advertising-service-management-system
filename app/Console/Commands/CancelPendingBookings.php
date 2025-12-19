<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CancelPendingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-pending-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bookings with status "pending" after 3 business days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cancelled = Booking::expired()->update(['status', 'cancelled']);
            
        $this->info("Cancelled {$cancelled} bookings with available assets");
    }
}
