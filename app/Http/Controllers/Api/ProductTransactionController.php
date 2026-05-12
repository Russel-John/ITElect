<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductTransactionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => InventoryTransaction::query()
                ->with('product')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function stockIn(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $result = DB::transaction(function () use ($product, $validated) {
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);

            $lockedProduct->increment('quantity', $validated['quantity']);
            $lockedProduct->refresh();

            $transaction = $this->recordTransaction($lockedProduct, 'stock_in', $validated);

            return [$lockedProduct, $transaction];
        });

        return response()->json([
            'message' => 'Stock-in transaction completed successfully.',
            'data' => [
                'product' => $result[0],
                'transaction' => $result[1],
            ],
        ], 201);
    }

    public function stockOut(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $result = DB::transaction(function () use ($product, $validated) {
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);

            if ($lockedProduct->quantity < $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => ['Not enough stock available.'],
                ]);
            }

            $lockedProduct->decrement('quantity', $validated['quantity']);
            $lockedProduct->refresh();

            $transaction = $this->recordTransaction($lockedProduct, 'stock_out', $validated);

            return [$lockedProduct, $transaction];
        });

        return response()->json([
            'message' => 'Stock-out transaction completed successfully.',
            'data' => [
                'product' => $result[0],
                'transaction' => $result[1],
            ],
        ], 201);
    }

    private function recordTransaction(Product $product, string $type, array $data): InventoryTransaction
    {
        $unitPrice = $data['unit_price'] ?? $product->price;

        return InventoryTransaction::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $data['quantity'],
            'unit_price' => $unitPrice,
            'total_amount' => $unitPrice * $data['quantity'],
            'remarks' => $data['remarks'] ?? null,
        ]);
    }
}
