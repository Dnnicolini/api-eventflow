<?php

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists locations', function () {
    Location::factory()->count(3)->create();

    $response = $this->getJson('/api/locations');

    $response->assertOk();

    expect($response->json('data'))->toHaveCount(3);
});

it('creates a location', function () {
    $payload = [
        'name' => 'Central Park',
        'latitude' => 40.785091,
        'longitude' => -73.968285,
        'address' => 'New York, NY',
    ];

    $response = $this->postJson('/api/locations', $payload);

    $response
        ->assertCreated()
        ->assertJson([
            'message' => 'Location created successfully',
            'data' => [
                'name' => 'Central Park',
                'latitude' => 40.785091,
                'longitude' => -73.968285,
                'address' => 'New York, NY',
            ],
        ]);

    $this->assertDatabaseHas('locations', [
        'name' => 'Central Park',
        'latitude' => 40.785091,
        'longitude' => -73.968285,
        'address' => 'New York, NY',
    ]);
});

it('shows a location', function () {
    $location = Location::factory()->create();

    $response = $this->getJson('/api/locations/' . $location->id);

    $response
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $location->id,
                'name' => $location->name,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'address' => $location->address,
            ],
        ]);
});

it('updates a location', function () {
    $location = Location::factory()->create([
        'name' => 'Old Venue',
        'latitude' => 10.1234567,
        'longitude' => 20.7654321,
        'address' => 'Old address',
    ]);

    $payload = [
        'name' => 'New Venue',
        'latitude' => 11.0000000,
        'longitude' => 21.0000000,
        'address' => 'New address',
    ];

    $response = $this->putJson('/api/locations/' . $location->id, $payload);

    $response
        ->assertOk()
        ->assertJson([
            'message' => 'Location updated successfully',
            'data' => [
                'id' => $location->id,
                'name' => 'New Venue',
                'latitude' => 11.0,
                'longitude' => 21.0,
                'address' => 'New address',
            ],
        ]);

    $this->assertDatabaseHas('locations', [
        'id' => $location->id,
        'name' => 'New Venue',
        'latitude' => 11.0,
        'longitude' => 21.0,
        'address' => 'New address',
    ]);
});

it('deletes a location', function () {
    $location = Location::factory()->create();

    $response = $this->deleteJson('/api/locations/' . $location->id);

    $response->assertStatus(204);

    $this->assertDatabaseMissing('locations', [
        'id' => $location->id,
    ]);
});
