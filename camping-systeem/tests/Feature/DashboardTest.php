<?php

use App\Models\Booking;
use App\Models\CampingSpot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated users can see bookings and customer details on the dashboard', function () {
    $user = User::factory()->create();
    $campingSpot = CampingSpot::factory()->create([
        'name' => 'Bosrand 12',
    ]);

    Booking::factory()
        ->for($campingSpot)
        ->create([
            'guest_name' => 'Jan de Vries',
            'guest_email' => 'jan@example.com',
            'guest_phone' => '0612345678',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-08',
            'party_size' => 3,
        ]);

    $response = $this
        ->actingAs($user)
        ->get(route('dashboard'));

    $response
        ->assertSuccessful()
        ->assertSee('Reserveringen')
        ->assertSee('Klanten')
        ->assertSee('Jan de Vries')
        ->assertSee('jan@example.com')
        ->assertSee('0612345678')
        ->assertSee('Bosrand 12')
        ->assertSee('01-07-2026')
        ->assertSee('08-07-2026')
        ->assertViewHas('bookings', fn ($bookings): bool => $bookings->first()->relationLoaded('campingSpot'))
        ->assertViewHas('customers', fn ($customers): bool => $customers->first()->guest_email === 'jan@example.com');
});
