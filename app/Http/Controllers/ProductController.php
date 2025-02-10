<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->product_id) {
            $product = Product::findOrFail($request->product_id);
            $product->update($data);
        } else {
            Product::create($data);
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được lưu thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa.');
    }
}
