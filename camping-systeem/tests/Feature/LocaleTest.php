<?php

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
        ->assertSee('Buchen');
});

test('unsupported languages are not accepted', function () {
    $this
        ->get(route('locale.update', 'fr'))
        ->assertNotFound();
});
