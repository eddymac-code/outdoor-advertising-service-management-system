<?php

use Flux\Flux;
use App\Models\Asset;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, rules, mount, on, title, protect};

title('Assets');
state([
    'types', 'selectedType', 'name', 'code', 'types', 'location',
    'latitude', 'longitude', 'size', 'monthly_price', 'asset' 
]);

rules([
    'name' => 'required',
    'code' => 'unique:assets',
    'selectedType' => 'required',
    'location' => 'required',
]);

mount(function () {
    $this->types = [
        ['name' => 'Billboard', 'value' => 'billboard'],
        ['name' => 'Jumbotron', 'value' => 'jumbotron']
    ];
});

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

    session()->flash('success', 'Asset created successfully!');

    // Emit event to close modal
    $this->dispatch('closeAssetModal');

    // Emit refresh event
    $this->dispatch('pg:eventRefresh-assetTable');
};

$update = protect(function ($id) {
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
});

on(['show-success-message' => function ($message) {
    session()->flash('success', $message);
}]);
?>

<div>
    <livewire:custom-header
        title="Assets"
        :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => 'dashboard'],
            ['name' => 'Assets', 'route' => 'assets']
        ]"
    />

    <x-button label="Add Asset" x-on:click="$dispatch('create-asset')" positive class="my-3" />

    @if (session('success'))
        <x-alert
            x-data="{ visible: true }" 
            x-show="visible" 
            x-init="setTimeout(() => visible = false, 3000)"  
            title="{{ session('success') }}" positive solid class="my-2"
        />
    @endif

    <div class="mt-2">
        <livewire:asset-table />
    </div>

    <livewire:assets.asset-form />
</div>
<script>
    document.addEventListener('closeAssetModal', function name() {
        $closeModal('assetFormModal'); 
    });

    document.addEventListener('openAssetModal', function name() {
        $openModal('assetFormModal'); 
    });
</script>
