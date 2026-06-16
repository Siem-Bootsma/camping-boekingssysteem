<?php

use App\Models\Booking;
use App\Models\CampingSpot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

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
        ->assertSee('href="mailto:jan@example.com"', false)
        ->assertSee('0612345678')
        ->assertSee('Bosrand 12')
        ->assertSee('01-07-2026')
        ->assertSee('08-07-2026')
        ->assertSee(__('Edit'))
        ->assertDontSee(__('Collapse'))
        ->assertViewHas('bookings', fn ($bookings): bool => $bookings->first()->relationLoaded('campingSpot'))
        ->assertViewHas('customers', fn ($customers): bool => $customers->first()->guest_email === 'jan@example.com');

    expect(substr_count($response->getContent(), 'href="mailto:jan@example.com"'))->toBe(2);
});

test('authenticated users can open edit controls for a booking', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()
        ->for(CampingSpot::factory())
        ->create([
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-08',
            'party_size' => 3,
        ]);

    $response = $this
        ->actingAs($user)
        ->get(route('dashboard', ['edit_booking' => $booking]));

    $response
        ->assertSuccessful()
        ->assertSee('name="start_date"', false)
        ->assertSee('value="2026-07-01"', false)
        ->assertSee('name="end_date"', false)
        ->assertSee('value="2026-07-08"', false)
        ->assertSee('name="party_size"', false)
        ->assertSee(__('Collapse'))
        ->assertSee(e(route('dashboard')), false)
        ->assertSee(__('Cancel'));
});

test('authenticated users can update booking dates and party size from the dashboard', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    try {
        $user = User::factory()->create();
        $booking = Booking::factory()
            ->for(CampingSpot::factory()->create([
                'capacity' => 4,
            ]))
            ->create([
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-08',
                'party_size' => 2,
            ]);

        $response = $this
            ->actingAs($user)
            ->from(route('dashboard', ['edit_booking' => $booking]))
            ->patch(route('dashboard.bookings.update', $booking), [
                'start_date' => '2026-07-10',
                'end_date' => '2026-07-14',
                'party_size' => 4,
            ]);

        $response->assertRedirect(route('dashboard', ['edit_booking' => $booking]));

        $booking->refresh();

        expect($booking->start_date->toDateString())->toBe('2026-07-10')
            ->and($booking->end_date->toDateString())->toBe('2026-07-14')
            ->and($booking->party_size)->toBe(4);
    } finally {
        Carbon::setTestNow();
    }
});

test('authenticated users can cancel a booking from the dashboard', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()
        ->for(CampingSpot::factory())
        ->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('dashboard.bookings.destroy', $booking));

    $response->assertRedirect(route('dashboard'));

    $this->assertModelMissing($booking);
});

test('dashboard booking updates cannot overlap another confirmed booking', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    try {
        $user = User::factory()->create();
        $campingSpot = CampingSpot::factory()->create([
            'capacity' => 4,
        ]);

        $booking = Booking::factory()
            ->for($campingSpot)
            ->create([
                'start_date' => '2026-07-01',
                'end_date' => '2026-07-05',
                'party_size' => 2,
            ]);

        Booking::factory()
            ->for($campingSpot)
            ->create([
                'start_date' => '2026-07-10',
                'end_date' => '2026-07-14',
                'party_size' => 2,
            ]);

        $response = $this
            ->actingAs($user)
            ->from(route('dashboard', ['edit_booking' => $booking]))
            ->patch(route('dashboard.bookings.update', $booking), [
                'start_date' => '2026-07-11',
                'end_date' => '2026-07-13',
                'party_size' => 2,
            ]);

        $response
            ->assertRedirect(route('dashboard', ['edit_booking' => $booking]))
            ->assertSessionHasErrors('start_date');

        $booking->refresh();

        expect($booking->start_date->toDateString())->toBe('2026-07-01')
            ->and($booking->end_date->toDateString())->toBe('2026-07-05');
    } finally {
        Carbon::setTestNow();
    }
});
