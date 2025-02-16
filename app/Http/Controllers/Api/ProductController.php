<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Product::all(), 200);
    } 
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'is_active' => 'boolean',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $validated['image'] = $imagePath;
            }
    
            $product = Product::create($validated);
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid data', 'error' => $e->getMessage()], 400);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
{
    $validated = $request->only([
        'name', 'description', 'price', 'stock', 'image', 'is_active'
    ]);

    // Kiểm tra nếu có file ảnh mới thì lưu
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
        $validated['image'] = $imagePath;
    } elseif (!empty($validated['image']) && filter_var($validated['image'], FILTER_VALIDATE_URL)) {
        // Nếu ảnh là URL hợp lệ, giữ nguyên
        $product->image = $validated['image'];
    }

    $product->update($validated);
    return response()->json($product, 200);
}

    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }
}
