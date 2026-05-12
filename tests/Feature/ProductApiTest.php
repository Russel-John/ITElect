<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_crud_flow(): void
    {
        $createResponse = $this->postJson('/api/products', [
            'name' => 'Keyboard',
            'description' => 'Mechanical keyboard',
            'price' => 1500,
            'quantity' => 10,
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('data.name', 'Keyboard')
            ->assertJsonPath('data.quantity', 10);

        $productId = $createResponse->json('data.id');

        $this->getJson("/api/products/{$productId}")
            ->assertOk()
            ->assertJsonPath('data.name', 'Keyboard');

        $this->patchJson("/api/products/{$productId}", [
            'price' => 1750,
        ])
            ->assertOk()
            ->assertJsonPath('data.price', '1750.00');

        $this->deleteJson("/api/products/{$productId}")
            ->assertOk();

        $this->assertDatabaseMissing('products', [
            'id' => $productId,
        ]);
    }

    public function test_stock_in_and_stock_out_transactions_update_inventory(): void
    {
        $product = Product::create([
            'name' => 'Mouse',
            'description' => 'Wireless mouse',
            'price' => 750,
            'quantity' => 5,
        ]);

        $this->postJson("/api/products/{$product->id}/stock-in", [
            'quantity' => 7,
            'remarks' => 'Supplier delivery',
        ])
            ->assertCreated()
            ->assertJsonPath('data.product.quantity', 12)
            ->assertJsonPath('data.transaction.type', 'stock_in');

        $this->postJson("/api/products/{$product->id}/stock-out", [
            'quantity' => 4,
            'remarks' => 'Customer order',
        ])
            ->assertCreated()
            ->assertJsonPath('data.product.quantity', 8)
            ->assertJsonPath('data.transaction.type', 'stock_out');

        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'type' => 'stock_in',
            'quantity' => 7,
        ]);

        $this->assertDatabaseHas('inventory_transactions', [
            'product_id' => $product->id,
            'type' => 'stock_out',
            'quantity' => 4,
        ]);
    }

    public function test_stock_out_rejects_insufficient_stock(): void
    {
        $product = Product::create([
            'name' => 'Monitor',
            'description' => '24 inch monitor',
            'price' => 6500,
            'quantity' => 2,
        ]);

        $this->postJson("/api/products/{$product->id}/stock-out", [
            'quantity' => 3,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->assertDatabaseCount('inventory_transactions', 0);
        $this->assertSame(2, $product->fresh()->quantity);
    }
}
