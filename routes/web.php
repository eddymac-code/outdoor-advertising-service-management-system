<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::prefix('assets')->group(function () {
        Volt::route('/', 'assets/index')->name('assets');
    });

    Route::prefix('clients')->group(function () {
        Volt::route('/', 'clients/index')->name('clients');
        Route::prefix('bookings')->group(function () {
            Volt::route('/', 'clients/bookings/index')->name('clients.bookings');
            Volt::route('/{id}/details', 'clients/bookings/details')->name('clients.bookings.details');    
        });
        // Volt::route('/bookings', 'clients/bookings/index')->name('clients.bookings');
        Volt::route('/contracts', 'clients/contracts/index')->name('clients.contracts');
    });

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
