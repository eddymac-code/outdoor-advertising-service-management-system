<?php

use App\Models\Booking;
use Livewire\Volt\Component;
use function Livewire\Volt\{state, on, mount, title, protect};

title('Bookings');

on([
    'show-success-message' => function ($message) {
        session()->flash('success', $message);
    },

    'delete-booking' => function ($bookingId) {
        $booking = Booking::findOrFail($bookingId);

        $booking->delete();

        $this->dispatch('pg:eventRefresh-bookingTable');

        $this->dispatch('show-success-message', 'Booking Deleted');
    }
]);
?>

<div>
    <livewire:custom-header
        title="Clients"
        :breadcrumbs="[
            ['name' => 'Dashboard', 'route' => 'dashboard'],
            ['name' => 'Clients', 'route' => 'clients'],
            ['name' => 'Bookings', 'route' => 'clients.bookings']
        ]"
    />

    <x-button label="Add Booking" x-on:click="$dispatch('create-booking')" positive class="my-3" />

    @if (session('success'))
        <x-alert
            x-data="{ visible: true }" 
            x-show="visible" 
            x-init="setTimeout(() => visible = false, 3000)"  
            title="{{ session('success') }}" positive solid class="my-2"
        />
    @endif

    <div class="mt-2">
        <livewire:booking-table />
    </div>

    <livewire:clients.bookings.booking-form />
</div>

<script>
    document.addEventListener('closeBookingModal', function name() {
        $closeModal('bookingFormModal'); 
    });

    document.addEventListener('openBookingModal', function name() {
        $openModal('bookingFormModal'); 
    });
</script>