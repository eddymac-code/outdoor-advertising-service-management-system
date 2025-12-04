<?php

use App\Models\Asset;
use App\Models\Client;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, on, mount, title, protect};

$clients = Client::all();
state([
    'clients' => $clients
        ->map( fn($client) => [ 'name' => $client->name, 'value' => $client->id])
        ->toArray(),
    'assets' => Asset::all()
        ->map(fn($asset) => [ 'name' => $asset->name, 'value' => $asset->id])
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

on([
    'create-booking' => fn() => $this->resetBookingForm()
]);

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
?>

<div>
    <x-modal-card :title="$title" name="bookingFormModal" id="bookingFormModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="mt-3">
                <div class="mt-3">
                    <x-select label="Select Client" placeholder="Select one client"
                        :options="$clients" wire:model="client_id" option-label="name" option-value="value"
                    />
                </div>
            </div>
            <div class="mt-3">
                <div class="mt-3">
                    <x-select label="Select Asset" placeholder="Select one asset i.e billboard"
                        :options="$assets" wire:model="asset_id" option-label="name" option-value="value"
                    />
                </div>
            </div>
            <div class="mt-3">
                <x-datetime-picker
                    wire:model="start_date"
                    label="Start Date"
                    placeholder="Start Date"
                    parse-format="YYYY-MM-DD"
                    without-time=true
                />
            </div>
            <div class="mt-3">
                <x-datetime-picker
                    wire:model="end_date"
                    label="End Date"
                    placeholder="End Date"
                    parse-format="YYYY-MM-DD"
                    without-time=true
                />                
            </div>
            <div class="mt-3">
                <x-input 
                    label="Total Price"
                    placeholder="Total Price" 
                    wire:model="total_price"
                />                
            </div>
            <div class="mt-3">
                <x-select label="Select Status" placeholder="Select one status"
                    :options="$statuses" wire:model="status" option-label="name" option-value="value"
                />
            </div>
        </div>    
    
        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-button danger label="Cancel" x-on:click="close" />

            <x-button wire:click="{{ $actionMethod }}" primary :label="$buttonLabel" />
        </x-slot>
    </x-modal-card>
</div>
