<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('the seeded admin can log in', function () {
    User::factory()->create([
        'email' => 'vuurvlieg@gmail.com',
        'password' => Hash::make('Admin123'),
    ]);

    $this
        ->withSession(['_token' => 'test-token'])
        ->post(route('login'), [
            '_token' => 'test-token',
            'email' => 'vuurvlieg@gmail.com',
            'password' => 'Admin123',
        ])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
});

test('guests are redirected to the admin login before viewing the dashboard', function () {
    $this
        ->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('visitors can still open the booking page without logging in', function () {
    $this
        ->get(route('bookings.create'))
        ->assertSuccessful();
});
