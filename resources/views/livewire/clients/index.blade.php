<?php

use App\Models\Client;
use Livewire\Volt\Component;
use function Livewire\Volt\{on, title, protect};

title('Clients');

on([
    'show-success-message' => function ($message) {
        session()->flash('success', $message);
    },
    
    'delete-client' => function ($clientId) {
        $client = Client::findOrFail($clientId);

        $client->delete();

        $this->dispatch('pg:eventRefresh-clientTable');

        $this->dispatch('show-success-message', 'Client Deleted');
    }
]);
?>

<div>
    <livewire:custom-header
        title="Clients"
        :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => 'dashboard'],
            ['name' => 'Clients', 'route' => 'clients']
        ]"
    />

    <x-button label="Add Client" x-on:click="$dispatch('create-client')" positive class="my-3" />

    @if (session('success'))
        <x-alert
            x-data="{ visible: true }" 
            x-show="visible" 
            x-init="setTimeout(() => visible = false, 3000)"  
            title="{{ session('success') }}" positive solid class="my-2"
        />
    @endif

    <div class="mt-2">
        <livewire:client-table />
    </div>

    <livewire:clients.client-form />
</div>
<script>
    document.addEventListener('closeClientModal', function name() {
        $closeModal('clientFormModal'); 
    });

    document.addEventListener('openClientModal', function name() {
        $openModal('clientFormModal'); 
    });
</script>