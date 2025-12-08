<?php

use Carbon\Carbon;
use App\Models\Asset;
use App\Models\Client;
use App\Models\Booking;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, rules, on, mount, title, protect, computed, updated};

$clients = Client::all();
state([
    'clients' => $clients
        ->map( fn($client) => [ 'name' => $client->name, 'value' => $client->id])
        ->toArray(),
    'assets' => Asset::all()
        ->map(fn($asset) => [ 'name' => $asset->name, 'value' => $asset->id, 'price' => $asset->price_per_month])
        ->toArray(),
    'statuses' => [
        ['name' => 'Pending', 'value' => 'pending'],
        ['name' => 'Confirmed', 'value' => 'confirmed'],
        ['name' => 'Completed', 'value' => 'completed'],
        ['name' => 'Cancelled', 'value' => 'cancelled']
    ],
    'client_id' => null,
    'asset_id' => null,
    'start_date' => '',
    'end_date' => '',
    'total_price' => 0,
    'status' => 'pending',
    'title' => 'Add Booking',
    'buttonLabel' => 'Save Booking',
    'actionMethod' => 'save'
]);

rules(function () {
    if ($this->actionMethod === 'save') {
        return [
            'client_id' => 'required',
            'asset_id' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ];
    } else {
        return [
            'client_id' => 'required',
            'asset_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];    
    }
});

$calculateMonths = computed(function () {
    if (!$this->start_date || !$this->end_date) {
        return 0;
    }

    $start = Carbon::parse($this->start_date);
    $end = Carbon::parse($this->end_date);

    return $start->diffInMonths($end) ?: 1;
});

$total_price = computed(function () {
    if (!$this->asset_id || !$this->calculateMonths) return 0;
    $asset = Asset::find($this->asset_id);
    return $asset?->price_per_month * $this->calculateMonths ?? 0;
});

$updateTotalPrice = protect(function () {
    if ($this->asset_id && $this->calculateMonths) {
        $asset = Asset::find($this->asset_id);
        $this->total_price = $asset?->price_per_month * $this->calculateMonths ?? 0;
    } else {
        $this->total_price = 0;
    }

    $this->status = 'pending';
});

updated([
    'asset_id' => fn () => $this->updateTotalPrice(),
    'start_date' => fn () => $this->updateTotalPrice(),
    'end_date' => fn () => $this->updateTotalPrice(),
]);

$loadBooking = protect(function ($id) {
    $booking = Booking::findOrFail($id);

    $this->bookingId = $id;
    $this->title = "Edit Booking";
    $this->buttonLabel = "Update Booking";
    $this->actionMethod = "update($id)";

    $this->client_id = $booking->client_id;
    $this->asset_id = $booking->asset_id;
    $this->start_date = $booking->start_date;
    $this->end_date = $booking->end_date;
    $this->total_price = $booking->total_price;
    $this->status = $booking->status;

    $this->dispatch('openBookingModal');
});

$resetBookingForm = protect(function () {
    $this->reset([
        'client_id',
        'asset_id',
        'start_date',
        'end_date',
        'total_price',
        'status'
    ]);

    $this->title = 'Add Booking';
    $this->buttonLabel = 'Save Booking';
    $this->actionMethod = 'save';

    $this->dispatch('openBookingModal');
});

$updateAssetStatus = protect(function ($booking) {
    $asset = Asset::findOrFail($booking->asset_id);
    
    $statusMap = [
        'pending' => 'on_hold',
        'confirmed' => 'pre_booked', 
        'completed' => 'booked',
        'cancelled' => 'available'
    ];
    
    $assetStatus = $statusMap[$booking->status] ?? 'available';
    $asset->update(['status' => $assetStatus]);
});

on([
    'create-booking' => fn() => $this->resetBookingForm(),
    'edit-booking' => fn ($bookingId) => $this->loadBooking($bookingId),
]);

$save = function () {
    $this->validate();

    $booking = Booking::create([
        'client_id' => $this->client_id,
        'asset_id' => $this->asset_id,
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'total_price' => $this->total_price,
        'status' => $this->status
    ]);
    
    $this->updateAssetStatus($booking);

    $this->dispatch('closeBookingModal');

    $this->dispatch('pg:eventRefresh-bookingTable');

    $this->dispatch('show-success-message', 'Booking Successfully added!');
};

$update = function ($id) {
    $this->validate();

    $this->booking = Booking::findOrFail($id);

    $this->booking->update([
        'client_id' => $this->client_id,
        'asset_id' => $this->asset_id,
        'start_date' => $this->start_date,
        'end_date' => $this->end_date,
        'total_price' => $this->total_price,
        'status' => $this->status
    ]);

    $this->updateAssetStatus($this->booking);

    $this->dispatch('closeBookingModal');

    $this->dispatch('pg:eventRefresh-bookingTable');

    $this->dispatch('show-success-message', 'Booking Successfully updated!');
};

?>

<div>
    <x-modal-card :title="$title" name="bookingFormModal" id="bookingFormModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="mt-3">
                <div class="mt-3">
                    <x-select label="Select Client" placeholder="Select one client"
                        :options="$clients" wire:model="client_id" option-label="name" option-value="value"
                        wire:click="save"
                    />
                </div>
            </div>
            <div class="mt-3">
                <div class="mt-3">
                    <x-select label="Select Asset" placeholder="Select one asset i.e billboard"
                        :options="$assets" wire:model.live="asset_id" option-label="name" option-value="value"
                    />
                </div>
            </div>
            <div class="mt-3">
                <x-datetime-picker
                    wire:model.live="start_date"
                    label="Start Date"
                    placeholder="Start Date"
                    parse-format="YYYY-MM-DD"
                    without-time=true
                    x-on:change="$dispatch('change-start-date')"
                />
            </div>
            <div class="mt-3">
                <x-datetime-picker
                    wire:model.live="end_date"
                    label="End Date"
                    placeholder="End Date"
                    parse-format="YYYY-MM-DD"
                    without-time=true
                    x-on:change="$dispatch('change-end-date')"
                />                
            </div>
            <div class="mt-3">
                <x-input 
                    label="Total Price"
                    :value="number_format($total_price, 2)"
                    readonly
                />                
            </div>
            <div class="mt-3">
                <x-select label="Select Status" placeholder="Select one status"
                    :options="$statuses" wire:model.live="status" option-label="name" option-value="value"
                />
            </div>
        </div>    
    
        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-button danger label="Cancel" x-on:click="close" />

            <x-button wire:click="{{ $actionMethod }}" primary :label="$buttonLabel" />
        </x-slot>
    </x-modal-card>
</div>
