@extends('products.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Quản lý Sản phẩm</h2>

    {{-- Thông báo  --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORM THÊM SẢN PHẨM --}}
    <div class="card mb-4">
        <div class="card-header">Thêm Sản phẩm</div>
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>

    {{-- FORM SỬA SẢN PHẨM (ẨN MẶC ĐỊNH) --}}
    <div class="card mb-4" id="editFormContainer" style="display: none;">
        <div class="card-header">Sửa Sản phẩm</div>
        <div class="card-body">
            <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_product_id" name="product_id">

                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" id="edit_price" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" id="edit_stock" name="stock" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình ảnh</label>
                    <input type="file" id="edit_image" name="image" class="form-control">
                    <div id="editImagePreview" style="display: none; margin-top: 10px;">
                        <p>Ảnh hiện tại:</p>
                        <img id="editCurrentImage" src="" alt="Product Image" width="100">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea id="edit_description" name="description" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-warning">Cập nhật</button>
                <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Hủy</button>
            </form>
        </div>
    </div>

    {{-- DANH SÁCH SẢN PHẨM --}}
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Hình ảnh</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }} VND</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" width="50">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ Str::limit($product->description, 50) }}</td>
                    <td>
                        <button onclick="showEditForm({{ $product }})" class="btn btn-warning btn-sm">Sửa</button>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function showEditForm(product) {
        document.getElementById('editFormContainer').style.display = "block";
        document.getElementById('edit_product_id').value = product.id;
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock').value = product.stock;
        document.getElementById('edit_description').value = product.description;

        if (product.image) {
            document.getElementById('editCurrentImage').src = "/storage/" + product.image;
            document.getElementById('editImagePreview').style.display = "block";
        } else {
            document.getElementById('editImagePreview').style.display = "none";
        }

        document.getElementById('editForm').action = "/products/" + product.id;
    }

    function hideEditForm() {
        document.getElementById('editFormContainer').style.display = "none";
    }
</script>
@endsection
