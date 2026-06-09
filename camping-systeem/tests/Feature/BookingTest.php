<?php

use App\Models\Booking;
use App\Models\CampingSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('a guest can book an available camping spot', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    $campingSpot = CampingSpot::factory()->create([
        'capacity' => 4,
    ]);

    $response = $this
        ->withSession(['_token' => 'test-token'])
        ->post(route('bookings.store'), [
            '_token' => 'test-token',
            'camping_spot_id' => $campingSpot->id,
            'guest_name' => 'Jan de Vries',
            'guest_email' => 'jan@example.com',
            'guest_phone' => '0612345678',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-08',
            'party_size' => 3,
        ]);

    $booking = Booking::query()->first();

    expect($booking)->not->toBeNull();

    $response->assertRedirect(route('bookings.show', $booking));

    expect($booking->guest_name)->toBe('Jan de Vries');
    expect($booking->camping_spot_id)->toBe($campingSpot->id);
    expect($booking->status)->toBe(Booking::STATUS_CONFIRMED);
});

test('the booking page lists available camping spots for the selected dates', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    $availableSpot = CampingSpot::factory()->create([
        'name' => 'Bosrand 12',
        'capacity' => 4,
        'price_per_night' => 45,
    ]);

    $bookedSpot = CampingSpot::factory()->create([
        'name' => 'Duinpan 3',
        'capacity' => 4,
    ]);

    Booking::factory()
        ->for($bookedSpot)
        ->create([
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-08',
        ]);

    $response = $this->get(route('bookings.create', [
        'start_date' => '2026-07-02',
        'end_date' => '2026-07-06',
        'party_size' => 3,
    ]));

    $response
        ->assertSuccessful()
        ->assertSee($availableSpot->name)
        ->assertSee(__('Price per night'))
        ->assertSee(__('You are booking :name.', ['name' => $availableSpot->name]))
        ->assertSee('name="camping_spot_id" value="'.$availableSpot->id.'"', false)
        ->assertDontSee($bookedSpot->name);
});

test('the booking page filters camping spots by selected price and capacity choices', function () {
    CampingSpot::factory()->create([
        'name' => 'Budgetplaats',
        'capacity' => 2,
        'price_per_night' => 35,
        'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
    ]);

    CampingSpot::factory()->create([
        'name' => 'Luxeplaats',
        'capacity' => 6,
        'price_per_night' => 85,
        'accommodation_type' => CampingSpot::TYPE_CHALET,
    ]);

    $response = $this->get(route('bookings.create', [
        'accommodation_types' => [CampingSpot::TYPE_TENT_PITCH],
        'capacity_ranges' => ['small'],
        'price_ranges' => ['budget'],
    ]));

    $response
        ->assertSuccessful()
        ->assertSee(__('Filter your search'))
        ->assertSee(__('Accommodation type'))
        ->assertSee(__('Price range'))
        ->assertSee('name="accommodation_types[]" value="'.CampingSpot::TYPE_TENT_PITCH.'" checked', false)
        ->assertSee('name="price_ranges[]" value="budget" checked', false)
        ->assertSee('name="capacity_ranges[]" value="small" checked', false)
        ->assertSee('Budgetplaats')
        ->assertDontSee('Luxeplaats');
});

test('a camping spot cannot be double booked for overlapping dates', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    $campingSpot = CampingSpot::factory()->create();

    Booking::factory()
        ->for($campingSpot)
        ->create([
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-08',
        ]);

    $response = $this
        ->from('/')
        ->withSession(['_token' => 'test-token'])
        ->post(route('bookings.store'), [
            '_token' => 'test-token',
            'camping_spot_id' => $campingSpot->id,
            'guest_name' => 'Piet Jansen',
            'guest_email' => 'piet@example.com',
            'start_date' => '2026-07-05',
            'end_date' => '2026-07-10',
            'party_size' => 2,
        ]);

    $response
        ->assertRedirect('/')
        ->assertSessionHasErrors('start_date');

    expect(Booking::query()->count())->toBe(1);
});

test('a booking can start on the day another booking ends', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    $campingSpot = CampingSpot::factory()->create();

    Booking::factory()
        ->for($campingSpot)
        ->create([
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-08',
        ]);

    $response = $this
        ->withSession(['_token' => 'test-token'])
        ->post(route('bookings.store'), [
            '_token' => 'test-token',
            'camping_spot_id' => $campingSpot->id,
            'guest_name' => 'Sara Bakker',
            'guest_email' => 'sara@example.com',
            'start_date' => '2026-07-08',
            'end_date' => '2026-07-12',
            'party_size' => 2,
        ]);

    $response->assertRedirect();

    expect(Booking::query()->count())->toBe(2);
});
