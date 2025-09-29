<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists categories', function () {
    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/categories');

    $response
        ->assertOk();

    expect($response->json('data'))->toHaveCount(3);
});

it('creates a category', function () {
    $payload = ['name' => 'Music'];

    $response = $this->postJson('/api/categories', $payload);

    $response
        ->assertCreated()
        ->assertJson([
            'message' => 'Category created successfully',
            'data' => [
                'name' => 'Music',
                'slug' => 'music',
            ],
        ]);

    $this->assertDatabaseHas('categories', [
        'name' => 'Music',
        'slug' => 'music',
    ]);
});

it('shows a category', function () {
    $category = Category::factory()->create();

    $response = $this->getJson('/api/categories/' . $category->id);

    $response
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ],
        ]);
});

it('updates a category', function () {
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'slug' => 'old-name',
    ]);

    $response = $this->putJson('/api/categories/' . $category->id, [
        'name' => 'New Name',
    ]);

    $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Category updated successfully',
            'data' => [
                'id' => $category->id,
                'name' => 'New Name',
                'slug' => 'new-name',
            ],
        ]);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
        'slug' => 'new-name',
    ]);
});

it('deletes a category', function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson('/api/categories/' . $category->id);

    $response
        ->assertStatus(204);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
