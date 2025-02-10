@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Sửa Sản phẩm</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" name="image" class="form-control">
                    @if ($product->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}" width="100">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control">{{ $product->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Cập nhật</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
