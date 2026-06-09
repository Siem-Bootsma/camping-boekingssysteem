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
        'image_path' => 'images/tentplaats1.png',
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
        ->assertSee(__('From'))
        ->assertSee(__('Click for details and prices'))
        ->assertSee(asset('images/tentplaats1.png'), false)
        ->assertSee(e(route('bookings.spots.show', [
            'campingSpot' => $availableSpot,
            'start_date' => '2026-07-02',
            'end_date' => '2026-07-06',
            'party_size' => 3,
        ])), false)
        ->assertDontSee(__('You are booking :name.', ['name' => $availableSpot->name]))
        ->assertDontSee($bookedSpot->name);
});

test('a guest can open a camping spot detail page before reserving', function () {
    Carbon::setTestNow('2026-06-01 10:00:00');

    $campingSpot = CampingSpot::factory()->create([
        'name' => 'Chalet Meerzicht',
        'capacity' => 5,
        'price_per_night' => 95,
        'accommodation_type' => CampingSpot::TYPE_CHALET,
        'image_path' => 'images/chalet2.png',
    ]);

    $response = $this->get(route('bookings.spots.show', [
        'campingSpot' => $campingSpot,
        'start_date' => '2026-07-02',
        'end_date' => '2026-07-06',
        'party_size' => 3,
    ]));

    $response
        ->assertSuccessful()
        ->assertSee('Chalet Meerzicht')
        ->assertSee(__('Back to results'))
        ->assertSee(__('Total estimate'))
        ->assertSee(asset('images/chalet2.png'), false)
        ->assertSee('name="camping_spot_id" value="'.$campingSpot->id.'"', false)
        ->assertSee('name="guest_name"', false)
        ->assertSee('name="start_date" value="2026-07-02"', false)
        ->assertSee('name="end_date" value="2026-07-06"', false);
});

test('the booking page filters camping spots by selected price range and capacity choices', function () {
    CampingSpot::factory()->create([
        'name' => 'Goedkopeplaats',
        'capacity' => 2,
        'price_per_night' => 27,
        'accommodation_type' => CampingSpot::TYPE_TENT_PITCH,
    ]);

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
        'min_price' => 30,
        'max_price' => 40,
    ]));

    $response
        ->assertSuccessful()
        ->assertSee(__('Filter your search'))
        ->assertSee('data-auto-filter-form', false)
        ->assertSee(__('Accommodation type'))
        ->assertSee(__('Min price per night'))
        ->assertSee(__('Max price per night'))
        ->assertSee('name="accommodation_types[]" value="'.CampingSpot::TYPE_TENT_PITCH.'" checked', false)
        ->assertSee('name="min_price"', false)
        ->assertSee('value="30"', false)
        ->assertSee('name="max_price"', false)
        ->assertSee('value="40"', false)
        ->assertSee('name="capacity_ranges[]" value="small" checked', false)
        ->assertSee('Budgetplaats')
        ->assertDontSee('Goedkopeplaats')
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
