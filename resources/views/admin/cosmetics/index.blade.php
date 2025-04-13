@extends('admin.layouts.app')

@section('title', 'Quản lý Mỹ phẩm')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Mỹ phẩm</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addCosmeticModal">
            <i class="fas fa-plus"></i> Thêm mỹ phẩm mới
        </button>
    </div>

    <!-- Content -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách mỹ phẩm</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Đánh giá</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cosmetics as $cosmetic)
                        <tr>
                            <td>{{ $cosmetic->id_cosmetic }}</td>
                            <td>
                                @if($cosmetic->image)
                                    <img src="{{ asset('storage/' . $cosmetic->image) }}" alt="{{ $cosmetic->name }}" width="50">
                                @else
                                    <img src="{{ asset('img/no-image.png') }}" alt="No image" width="50">
                                @endif
                            </td>
                            <td>{{ $cosmetic->name }}</td>
                            <td>{{ $cosmetic->category->name ?? 'N/A' }}</td>
                            <td>{{ number_format($cosmetic->price) }} VNĐ</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $cosmetic->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                ({{ $cosmetic->rating }})
                            </td>
                            <td>
                                @if($cosmetic->isHidden)
                                    <span class="badge badge-danger">Ẩn</span>
                                @else
                                    <span class="badge badge-success">Hiển thị</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info edit-cosmetic" 
                                        data-id="{{ $cosmetic->id_cosmetic }}"
                                        data-toggle="modal" 
                                        data-target="#editCosmeticModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-cosmetic"
                                        data-id="{{ $cosmetic->id_cosmetic }}"
                                        data-name="{{ $cosmetic->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-center">
                {{ $cosmetics->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Add Cosmetic Modal -->
<div class="modal fade" id="addCosmeticModal" tabindex="-1" role="dialog" aria-labelledby="addCosmeticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCosmeticModalLabel">Thêm mỹ phẩm mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.cosmetics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Tên mỹ phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="id_category">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-control" id="id_category" name="id_category" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Đánh giá</label>
                        <input type="number" class="form-control" id="rating" name="rating" min="0" max="5" step="0.1">
                    </div>
                    <div class="form-group">
                        <label for="image">Hình ảnh</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="isHidden" name="isHidden">
                            <label class="custom-control-label" for="isHidden">Ẩn sản phẩm</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Cosmetic Modal -->
<div class="modal fade" id="editCosmeticModal" tabindex="-1" role="dialog" aria-labelledby="editCosmeticModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCosmeticModalLabel">Chỉnh sửa mỹ phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCosmeticForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Tên mỹ phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_category">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_id_category" name="id_category" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id_category }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_price">Giá <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_price" name="price" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_rating">Đánh giá</label>
                        <input type="number" class="form-control" id="edit_rating" name="rating" min="0" max="5" step="0.1">
                    </div>
                    <div class="form-group">
                        <label for="edit_image">Hình ảnh</label>
                        <input type="file" class="form-control-file" id="edit_image" name="image">
                        <div id="current_image_container" class="mt-2">
                            <img id="current_image" src="" alt="Current image" style="max-width: 150px; display: none;">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="edit_isHidden" name="isHidden">
                            <label class="custom-control-label" for="edit_isHidden">Ẩn sản phẩm</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Xử lý khi nhấn nút sửa
        $('.edit-cosmetic').on('click', function() {
            const id = $(this).data('id');
            
            // Reset form
            $('#editCosmeticForm')[0].reset();
            
            // Lấy thông tin mỹ phẩm
            $.ajax({
                url: `/admin/cosmetics/${id}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Thiết lập action cho form
                    $('#editCosmeticForm').attr('action', `/admin/cosmetics/${id}`);
                    
                    // Điền thông tin vào form
                    $('#edit_name').val(response.cosmetic.name);
                    $('#edit_id_category').val(response.cosmetic.id_category);
                    $('#edit_price').val(response.cosmetic.price);
                    $('#edit_rating').val(response.cosmetic.rating);
                    $('#edit_isHidden').prop('checked', response.cosmetic.isHidden == 1);
                    
                    // Hiển thị ảnh hiện tại nếu có
                    if (response.cosmetic.image) {
                        $('#current_image').attr('src', `/storage/${response.cosmetic.image}`).show();
                    } else {
                        $('#current_image').hide();
                    }
                },
                error: function(error) {
                    console.error('Error fetching cosmetic:', error);
                    alert('Có lỗi xảy ra khi lấy thông tin mỹ phẩm.');
                }
            });
        });
        
        // Xử lý xóa mỹ phẩm
        $('.delete-cosmetic').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            if (confirm(`Bạn có chắc muốn xóa mỹ phẩm "${name}"?`)) {
                $.ajax({
                    url: `/admin/cosmetics/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra: ' + response.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi xóa mỹ phẩm.');
                    }
                });
            }
        });
    });
</script>
@endsection 