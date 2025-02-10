<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    /**
     * Hiển thị danh sách sản phẩm.
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Hiển thị form thêm sản phẩm.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Xử lý lưu sản phẩm vào database.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // Xử lý hình ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Lưu vào database
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm!');
    }

    /**
     * Hiển thị form sửa sản phẩm.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Xử lý cập nhật sản phẩm.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // Xử lý hình ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Lưu ảnh mới
            $product->image = $request->file('image')->store('products', 'public');
        }

        // Cập nhật thông tin
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $product->image,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    /**
     * Xóa sản phẩm.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh nếu có
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Xóa sản phẩm
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
    }
}
