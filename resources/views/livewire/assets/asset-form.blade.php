<?php

use App\Models\Asset;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, rules, mount, on, protect};

state([
    'assetId' => null,       // null: create, not null: edit
    'title' => 'Add Asset',
    'buttonLabel' => 'Save Asset',
    'actionMethod' => 'save',
    'name' => '',
    'code' => '',
    'selectedType' => '',
    'location' => '',
    'latitude' => '',
    'longitude' => '',
    'size' => '',
    'monthly_price' => '',
    'types' => [
        ['name' => 'Billboard', 'value' => 'billboard'],
        ['name' => 'Jumbotron', 'value' => 'jumbotron'],
    ],
]);

rules([
    'name' => 'required|string|max:255',
    'code' => 'required|string|max:255',
    'selectedType' => 'required',
    'location' => 'required|string',
    'latitude' => 'nullable|string',
    'longitude' => 'nullable|string',
    'size' => 'nullable|string',
    'monthly_price' => 'required|numeric',
]);

// Load an Asset for editing
$loadAsset = protect(function ($id) {
    $asset = Asset::findOrFail($id);

    $this->assetId = $id;
    $this->title = "Edit Asset";
    $this->buttonLabel = "Update Asset";
    $this->actionMethod = "update($id)";

    $this->name = $asset->name;
    $this->code = $asset->code;
    $this->selectedType = $asset->type;
    $this->location = $asset->location;
    $this->latitude = $asset->latitude;
    $this->longitude = $asset->longitude;
    $this->size = $asset->size;
    $this->monthly_price = $asset->price_per_month;

    $this->dispatch('openAssetModal');
});

// Reset state for adding new asset
$resetForm = protect(function () {
    $this->reset([
        'assetId',
        'name',
        'code',
        'selectedType',
        'location',
        'latitude',
        'longitude',
        'size',
        'monthly_price',
    ]);

    $this->title = "Add Asset";
    $this->buttonLabel = "Save Asset";
    $this->actionMethod = "save";

    $this->dispatch('openAssetModal');
});

on([
    'edit-asset' => fn ($assetId) => $this->loadAsset($assetId),
    'create-asset' => fn () => $this->resetForm(),
]);

$save = function () {
    $this->validate();

    $asset = Asset::create([
        'name' => $this->name,
        'code' => $this->code,
        'type' => $this->selectedType,
        'location' => $this->location,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'size' => $this->size,
        'price_per_month' => $this->monthly_price
    ]);

    // Emit event to close modal
    $this->dispatch('closeAssetModal');

    // Emit refresh event
    $this->dispatch('pg:eventRefresh-assetTable');

    $this->dispatch('show-success-message', 'Asset successfully added!');
};
// $update = fn() => dispatch('update-asset', ['id' => $this->assetId]);
$update = function ($id) {
    $this->validate();

    $this->asset = Asset::findOrFail($id);

    $this->asset->update([
        'name' => $this->name,
        'code' => $this->code,
        'type' => $this->selectedType,
        'location' => $this->location,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'size' => $this->size,
        'price_per_month' => $this->monthly_price
    ]);

    // Emit event to close modal
    $this->dispatch('closeAssetModal');

    // Emit refresh event
    $this->dispatch('pg:eventRefresh-assetTable');

    $this->dispatch('show-success-message', 'Asset successfully updated!');
};

?>

<div>
    <x-modal-card :title="$title" name="assetFormModal" id="assetFormModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="mt-3">
                <x-input 
                    label="Name"
                    placeholder="Name" 
                    wire:model="name"
                />
            </div>
            <div class="mt-3">
                <x-input 
                    label="Code"
                    placeholder="code" 
                    wire:model="code"
                />                
            </div>
            <div class="mt-3">
                <x-select label="Select Type" placeholder="Select a type"
                    :options="$types" wire:model="selectedType" option-label="name" option-value="value"
                />
            </div>
            <div class="mt-3">
                <x-input 
                    label="Location"
                    placeholder="Location" 
                    wire:model="location"
                />
            </div>
            <div class="mt-3">
                <x-input 
                    label="Latitude"
                    placeholder="Latitude" 
                    wire:model="latitude"
                />
            </div>
            <div class="mt-3">
                <x-input 
                    label="Longitude"
                    placeholder="Longitude" 
                    wire:model="longitude"
                />                
            </div>
            <div class="mt-3">
                <x-input 
                    label="Size"
                    placeholder="Size" 
                    wire:model="size"
                />
            </div>
            <div class="mt-3">
                <x-input 
                    label="Monthly Price"
                    placeholder="Price" 
                    wire:model="monthly_price"
                />                
            </div>
        </div>    
    
        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-button danger label="Cancel" x-on:click="close" />

            <x-button wire:click="{{ $actionMethod }}" primary :label="$buttonLabel" />
        </x-slot>
    </x-modal-card>
</div>
