<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_resource_returns_clean_404_response(): void
    {
        $this->getJson('/api/products/999999')
            ->assertNotFound()
            ->assertExactJson([
                'message' => 'Resource not found.',
            ]);
    }

    public function test_invalid_product_payload_returns_422_response(): void
    {
        $this->postJson('/api/products', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'price']);
    }

    public function test_malformed_json_returns_400_response(): void
    {
        $this->call(
            'POST',
            '/api/products',
            server: [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: '{bad json'
        )
            ->assertBadRequest()
            ->assertExactJson([
                'message' => 'Invalid JSON request body.',
            ]);
    }

    public function test_wrong_http_method_returns_clean_405_response(): void
    {
        $this->putJson('/api/transactions')
            ->assertMethodNotAllowed()
            ->assertExactJson([
                'message' => 'HTTP method not allowed for this endpoint.',
            ]);
    }
}
