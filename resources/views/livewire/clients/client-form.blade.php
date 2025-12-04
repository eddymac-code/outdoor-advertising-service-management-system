<?php

use App\Models\Client;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, rules, mount, on, protect};

state([
    'clientId' => null,
    'title' => 'Add Client',
    'buttonLabel' => 'Save Client',
    'actionMethod' => 'save',
    'name' => '',
    'company' => '',
    'email' => '',
    'phone' => ''
]);

rules([
    'name' => 'required|string|max:255',
    'email'=> 'required|string|max:255'
]);

// for editing client details
$loadClient = protect(function ($id) {
    $client = Client::findOrFail($id);

    $this->clientId = $id;
    $this->title = "Edit Client";
    $this->buttonLabel = "Update Client";
    $this->actionMethod = "update($id)";

    $this->name = $client->name;
    $this->company = $client->company;
    $this->email = $client->email;
    $this->phone = $client->phone;

    $this->dispatch('openClientModal');
});

// for new client
$resetClientForm = protect(function () {
    $this->reset([
        'clientId',
        'name',
        'company',
        'email',
        'phone'
    ]);

    $this->title = "Add Client";
    $this->buttonLabel = "Add Client";
    $this->actionMethod = "save";

    $this->dispatch('openClientModal');
});

on([
    'edit-client' => fn($clientId) => $this->loadClient($clientId),
    'create-client' => fn() => $this->resetClientForm()
]);

$save = function () {
    $this->validate();

    $client = Client::create([
        'name' => $this->name,
        'company' => $this->company,
        'email' => $this->email,
        'phone' => $this->phone
    ]);

    // Emit event to close modal
    $this->dispatch('closeClientModal');

    // Emit refresh event
    $this->dispatch('pg:eventRefresh-clientTable');

    $this->dispatch('show-success-message', 'Client successfully added!');
};

$update = function ($id) {
    $this->validate();

    $this->client = Client::findOrFail($id);

    $this->client->update([
        'name' => $this->name,
        'company' => $this->company,
        'email' => $this->email,
        'phone' => $this->phone,
        
    ]);

    // Emit event to close modal
    $this->dispatch('closeClientModal');

    // Emit refresh event
    $this->dispatch('pg:eventRefresh-clientTable');

    $this->dispatch('show-success-message', 'Client successfully updated!');
};

?>

<div>
    <x-modal-card :title="$title" name="clientFormModal" id="clientFormModal">
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
                    label="Company"
                    placeholder="Company" 
                    wire:model="company"
                />                
            </div>
            <div class="mt-3">
                <x-input 
                    label="Email"
                    placeholder="Email" 
                    wire:model="email"
                />                
            </div>
            <div class="mt-3">
                <x-input 
                    label="Phone"
                    placeholder="Phone" 
                    wire:model="phone"
                />
            </div>
        </div>    
    
        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-button danger label="Cancel" x-on:click="close" />

            <x-button wire:click="{{ $actionMethod }}" primary :label="$buttonLabel" />
        </x-slot>
    </x-modal-card>
</div>
