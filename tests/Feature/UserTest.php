<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('registers a user successfully', function () {
    $payload = [
        'name' => 'Daniele Nicolini',
        'email' => 'danienicolini@hotmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->postJson('/api/register', $payload);

    $response
        ->assertCreated()
        ->assertJson([
            'message' => 'User created successfully',
            'data' => [
                'name' => 'Daniele Nicolini',
                'email' => 'danienicolini@hotmail.com',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'danienicolini@hotmail.com',
    ]);
});

it('logs in with valid credentials', function () {
    User::factory()->create([
        'email' => 'danienicolini@hotmail.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'danienicolini@hotmail.com',
        'password' => 'password',
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'token',
            'message',
        ]);

    expect($response->json('token'))->not()->toBeEmpty();
});

it('logs out the authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('authToken')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson('/api/logout');

    $response
        ->assertOk()
        ->assertJson([
            'message' => 'User logged out successfully',
        ]);
});
