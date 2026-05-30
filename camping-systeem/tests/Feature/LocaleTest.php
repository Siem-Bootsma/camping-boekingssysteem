<?php

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a visitor can switch the application language', function () {
    $response = $this
        ->from('/')
        ->get(route('locale.update', 'de'));

    $response
        ->assertRedirect('/')
        ->assertSessionHas('locale', 'de');

    $this
        ->withSession(['locale' => 'de'])
        ->get(route('bookings.create'))
        ->assertSuccessful()
        ->assertSee('Finden Sie Ihren Platz zwischen Wald, Duenen und Lagerfeuer.')
        ->assertSee('Verfuegbarkeit pruefen');
});

test('the booking confirmation page uses the selected language', function () {
    $booking = Booking::factory()->create();

    $this
        ->withSession(['locale' => 'de'])
        ->get(route('bookings.show', $booking))
        ->assertSuccessful()
        ->assertSee('Buchung bestaetigt')
        ->assertSee('Ihr Platz ist reserviert.')
        ->assertSee('Neue Buchung');
});

test('unsupported languages are not accepted', function () {
    $this
        ->get(route('locale.update', 'fr'))
        ->assertNotFound();
});
