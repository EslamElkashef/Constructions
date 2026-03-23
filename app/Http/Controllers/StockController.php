<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    // list products with pagination + search
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('sku', 'like', "%{$q}%");
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('stock.index', compact('products'));
    }

    // store product
    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
            'low_stock_threshold' => 'nullable|numeric',
        ]);

        Product::create($data);

        return redirect()->route('stock.index')->with('success', 'Product created.');
    }

    // update product
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => "nullable|string|max:100|unique:products,sku,{$product->id}",
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'low_stock_threshold' => 'nullable|numeric',
        ]);

        $product->update($data);

        return redirect()->route('stock.index')->with('success', 'Product updated.');
    }

    // delete product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('stock.index')->with('success', 'Product deleted.');
    }

    // adjust stock (in / out) — creates movement and updates product quantity
    public function adjustStock(Request $request, Product $product)
    {
        $data = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $before = $product->quantity;
            $qty = (float) $data['quantity'];

            if ($data['type'] === 'out' && $qty > $before) {
                DB::rollBack();

                return response()->json(['success' => false, 'message' => 'Not enough stock to deduct.'], 400);
            }

            $after = $data['type'] === 'in' ? $before + $qty : $before - $qty;

            $product->update(['quantity' => $after]);

            \App\Models\StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $data['type'],
                'quantity' => $qty,
                'before_quantity' => $before,
                'after_quantity' => $after,
                'reference' => $data['reference'] ?? null,
                'reason' => $data['reason'] ?? null,
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    // show movements for a product
    public function movements(Request $request, Product $product)
    {
        $query = $product->movements()->with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->user.'%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('stock.movements', compact('product', 'movements'));
    }
}
