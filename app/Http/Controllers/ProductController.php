<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\TenantManager;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $tenant = app(TenantManager::class)->tenant();

        $products = Product::where('tenant_id', $tenant->id)
            ->with(['options', 'files'])
            ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['options', 'files']));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_cents' => ['sometimes', 'integer', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
        ]);

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
