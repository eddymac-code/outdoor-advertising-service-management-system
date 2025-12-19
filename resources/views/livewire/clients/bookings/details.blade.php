<?php

use Carbon\Carbon;

use App\Models\Booking;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, on, mount, title, protect, computed};

title('Booking Details');

state([
    'booking', 'start_date', 'end_date', 'duration', 'owner'
]);

mount(function ($id) {
    $this->booking = Booking::findOrFail($id);
    $this->start_date = $this->booking->start_date;
    $this->end_date = $this->booking->end_date;
    $this->duration = $this->monthsAndDays;
    $this->owner = $this->booking->client;
});

$monthsAndDays = computed(fn () => $this->end_date ? Carbon::parse($this->end_date)
->diff(Carbon::parse($this->start_date))->format('%m months and %d days') : 0);

?>

<div>
    <livewire:custom-header
        title="Booking Details"
        :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => 'dashboard'],
            ['name' => 'Clients', 'route' => 'clients'],
            ['name' => 'Bookings', 'route' => 'clients.bookings']
        ]"
    />

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-card 
            title="Booking Details" 
            class="mt-3"
        >
            <p>Asset: {{ $booking->asset->name }}</p>
            <p>Type: {{ Str::ucwords($booking->asset->type) }}</p>
            <p>Status: {{ Str::ucwords($booking->status) }}</p>
            <p>Total Price: {{ number_format($booking->total_price, 2) }}</p>
            <p>Duration: {{ $duration }}</p>
            <br>
            <p>Booking By: {{ $owner->name }}</p>
            <p>Of: {{ $owner->company }}</p>
        </x-card>
        <x-card
            title="Payments" 
            class="mt-3"
        >
            <x-button label="Add Payment" x-on:click="$dispatch('create-payment')" positive class="my-3" />
            <livewire:payment-table />
        </x-card>
    </div>
</div>
