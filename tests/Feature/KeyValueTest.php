<?php
namespace Tests\Feature;

use App\Models\KeyValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class KeyValueTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_requires_key()
    {
        $payload = ['value' => 'some value'];

        $response = $this->postJson('/api/object', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error'   => [
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => [
                        'key' => ['The key field is required.'],
                    ],
                ],
            ]);
    }

    public function test_store_requires_value()
    {
        $payload = ['key' => 'mykey'];

        $response = $this->postJson('/api/object', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error'   => [
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => [
                        'value' => ['The value field is required.'],
                    ],
                ],
            ]);
    }

    public function test_store_requires_key_to_be_string()
    {
        $payload = ['key' => ["12345", "myKey"], 'value' => 'some value'];

        $response = $this->postJson('/api/object', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error'   => [
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => [
                        'key' => ['The key field must be a string.'],
                    ],
                ],
            ]);
    }

    public function test_store_requires_key_to_be_max_255()
    {
        $payload = ['key' => encrypt('myKey') . '-' . encrypt('myKey'), 'value' => 'some value'];

        $response = $this->postJson('/api/object', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error'   => [
                    'code'    => 'VALIDATION_ERROR',
                    'message' => 'Validation failed',
                    'details' => [
                        'key' => ['The key field must not be greater than 255 characters.'],
                    ],
                ],
            ]);
    }

    public function test_can_store_a_key_value_pair()
    {
        $payload = ['key' => 'mykey', 'value' => 'value1'];

        $response = $this->postJson('/api/object', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment($payload);

        $this->assertDatabaseHas('key_values', [
            'key'   => 'mykey',
            'value' => json_encode('value1'),
        ]);
    }

    public function test_can_fetch_latest_value_by_key()
    {
        KeyValue::factory()->create(['key' => 'mykey', 'value' => 'value1']);
        KeyValue::factory()->create(['key' => 'mykey', 'value' => 'value2']);

        $response = $this->getJson('/api/object/mykey');

        $response->assertStatus(200)
            ->assertJsonFragment(['value' => 'value2']);
    }

    public function test_can_fetch_value_by_key_and_timestamp()
    {
        $record1 = KeyValue::factory()->create([
            'key'        => 'mykey',
            'value'      => 'value1',
            'created_at' => now()->subMinutes(10),
        ]);

        $record2 = KeyValue::factory()->create([
            'key'        => 'mykey',
            'value'      => 'value2',
            'created_at' => now(),
        ]);

        $ts = now()->subMinutes(5)->timestamp;

        $response = $this->getJson("/api/object/mykey?timestamp={$ts}");

        $response->assertStatus(200)
            ->assertJsonFragment(['value' => 'value1']);
    }

    public function test_returns_not_found_if_key_does_not_exist()
    {
        $response = $this->getJson('/api/object/nonexistent');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'error'   => [
                    'code' => 'NOT_FOUND',
                ],
            ]);
    }

    public function test_can_fetch_all_records_paginated()
    {
        KeyValue::factory()->count(5)->create();

        $response = $this->getJson('/api/object/get_all_records');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    public function test_blocked_web_routes_return_401_json()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'error'   => [
                    'code'    => "OK",
                    'message' => 'Healthy',
                    'details' => "App is up & running.",
                ],
            ]);

        $response = $this->get('/blocked');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'error'   => [
                    'code'    => 'UNAUTHORIZED',
                    'message' => 'Unauthorized',
                    'details' => 'Unauthorized web access.',
                ],
            ]);
    }
}
