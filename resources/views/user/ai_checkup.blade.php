@extends('user.theme.auth-layout')

@section('title')
{{ __('AI Skin CheckUp') }}
@endsection

@section('content')
<section class="page-title bg-1">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="block text-center">
                    <span class="text-white">O2Skin AI</span>
                    <h1 class="text-capitalize mb-5 text-lg">AI Skin CheckUp</h1>
                    <ul class="list-inline breadcumb-nav">
                        <li class="list-inline-item"><a href="{{ route('index') }}" class="text-white">{{ __('menu.home') }}</a></li>
                        <li class="list-inline-item"><span class="text-white">/</span></li>
                        <li class="list-inline-item"><span class="text-white-50">AI Skin CheckUp</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section ai-checkup-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Phân tích bệnh da liễu bằng AI</h3>

                        @if(isset($error))
                        <div class="alert alert-danger">
                            <i class="icofont-warning-alt mr-2"></i> {{ $error }}
                        </div>
                        @endif

                        <div class="text-center mb-4">
                            <p class="lead">Tải lên ảnh về tình trạng da của bạn để nhận phân tích</p>
                        </div>

                        <form action="{{ route('ai.analyze') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            <div class="upload-area text-center p-5 mb-4" id="uploadArea">
                                <div class="upload-icon mb-3">
                                    <i class="icofont-cloud-upload" style="font-size: 48px; color: #223a66;"></i>
                                </div>
                                <div class="upload-text">
                                    <h5>Kéo và thả hình ảnh vào đây</h5>
                                    <p>Hoặc nhấp vào để chọn file</p>
                                    <small class="text-muted">Hỗ trợ: JPG, JPEG, PNG (Tối đa 2MB)</small>
                                </div>
                                <input type="file" name="image" id="imageInput" class="d-none" accept="image/jpeg,image/png,image/jpg">
                                <div id="imagePreview" class="mt-3 d-none">
                                    <img src="" class="img-preview img-fluid rounded mx-auto d-block" style="max-height: 250px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
                                        <i class="icofont-trash"></i> Xóa ảnh
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mb-3">
                                <button type="submit" class="btn btn-main btn-lg px-5" id="analyzeBtn" disabled>
                                    <i class="icofont-search-document mr-2"></i>Phân tích ngay
                                </button>
                            </div>

                            <div class="tips mt-4">
                                <h5>Để có kết quả tốt nhất:</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="tip-item text-center p-3">
                                            <i class="icofont-light-bulb" style="font-size: 32px; color: #e12454;"></i>
                                            <p>Ánh sáng tự nhiên</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="tip-item text-center p-3">
                                            <i class="icofont-focus" style="font-size: 32px; color: #e12454;"></i>
                                            <p>Hình ảnh rõ nét</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="tip-item text-center p-3">
                                            <i class="icofont-eye-alt" style="font-size: 32px; color: #e12454;"></i>
                                            <p>Hiển thị vùng ảnh hưởng</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @if(isset($results))
                        <div class="mt-5 pt-3 border-top">
                            <h3 class="text-center mb-4">Kết quả phân tích</h3>
                            <div class="row">
                                <div class="col-md-5">
                                    @if(isset($imagePath))
                                    <div class="uploaded-image text-center">
                                        <img src="{{ $imagePath }}" class="img-fluid rounded shadow-sm mb-3" alt="Uploaded skin image">
                                        <p class="text-muted small">Hình ảnh đã tải lên</p>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <div class="results-card">
                                        @if(is_array($results))
                                        <div class="result-header mb-4">
                                            <h4 class="text-primary">Kết luận: {{ $results['predicted_class'] ?? 'Unknown' }}</h4>
                                            <div class="progress mt-2 mb-2" style="height: 10px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ isset($results['confidence']) ? number_format($results['confidence'] * 100, 2) : 0 }}%"
                                                    aria-valuenow="{{ isset($results['confidence']) ? number_format($results['confidence'] * 100, 2) : 0 }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <p>Độ tin cậy: <strong>{{ isset($results['confidence']) ? number_format($results['confidence'] * 100, 2) : 0 }}%</strong></p>
                                        </div>

                                        @if(isset($results['top_3_predictions']) && is_array($results['top_3_predictions']))
                                        <div class="predictions-list mb-4">
                                            <h5>Các dự đoán hàng đầu:</h5>
                                            <div class="list-group">
                                                @foreach($results['top_3_predictions'] as $index => $prediction)
                                                <div class="list-group-item list-group-item-action {{ $index === 0 ? 'active' : '' }}">
                                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                                        <h6 class="mb-1">{{ $prediction['class'] ?? 'Unknown' }}</h6>
                                                        <span class="badge {{ $index === 0 ? 'badge-light' : 'badge-primary' }}">
                                                            {{ isset($prediction['probability']) ? number_format($prediction['probability'] * 100, 2) : 0 }}%
                                                        </span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        @if(isset($results['description']))
                                        <div class="condition-info mb-4">
                                            <h5>Về tình trạng này:</h5>
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <p>{{ $results['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @else
                                        <div class="alert alert-info">
                                            <p>{{ $results }}</p>
                                        </div>
                                        @endif

                                        <div class="alert alert-warning mt-3">
                                            <div class="d-flex">
                                                <div class="mr-3">
                                                    <i class="icofont-warning-alt" style="font-size: 24px;"></i>
                                                </div>
                                                <div>
                                                    <strong>Lưu ý:</strong> Đây là dự đoán được tạo bởi AI và không nên thay thế cho lời khuyên y tế chuyên nghiệp. Vui lòng tham khảo ý kiến bác sĩ da liễu để có chẩn đoán chính xác.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <a href="{{ route('appoinment') }}" class="btn btn-main-2">
                                                <i class="icofont-calendar mr-2"></i>Đặt lịch khám với bác sĩ
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="sidebar-wrap pl-lg-4 mt-5 mt-lg-0">
                    <div class="sidebar-widget bg-light rounded p-4 mb-4">
                        <h4 class="mb-4">Về AI Skin CheckUp</h4>
                        <p>Công cụ phân tích da bằng AI của chúng tôi có thể giúp xác định các tình trạng da phổ biến bằng cách sử dụng thuật toán học máy tiên tiến.</p>
                        <hr>
                        <div class="media mb-3">
                            <div class="icon mr-3">
                                <i class="icofont-brain-alt" style="font-size: 32px; color: #e12454;"></i>
                            </div>
                            <div class="media-body">
                                <h5>Học máy tiên tiến</h5>
                                <p>AI được đào tạo với hàng nghìn hình ảnh da liễu để cung cấp đánh giá sơ bộ về tình trạng da.</p>
                            </div>
                        </div>
                        <div class="media mb-3">
                            <div class="icon mr-3">
                                <i class="icofont-safety" style="font-size: 32px; color: #e12454;"></i>
                            </div>
                            <div class="media-body">
                                <h5>Bảo mật dữ liệu</h5>
                                <p>Thông tin và hình ảnh của bạn được mã hóa và chỉ được sử dụng với mục đích phân tích.</p>
                            </div>
                        </div>
                        <div class="media">
                            <div class="icon mr-3">
                                <i class="icofont-doctor" style="font-size: 32px; color: #e12454;"></i>
                            </div>
                            <div class="media-body">
                                <h5>Hỗ trợ y khoa</h5>
                                <p>Công cụ giúp bác sĩ chẩn đoán sớm các vấn đề về da.</p>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-widget rounded bg-white shadow-sm p-4 mb-4">
                        <h4 class="mb-4">Cách thức hoạt động</h4>
                        <div class="steps">
                            <div class="step d-flex align-items-center mb-4">
                                <div class="step-icon mr-4">
                                    <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">1</span>
                                </div>
                                <div class="step-content">
                                    <h5 class="mb-1">Tải lên hình ảnh</h5>
                                    <p class="mb-0">Chụp ảnh rõ nét vùng da cần kiểm tra</p>
                                </div>
                            </div>
                            <div class="step d-flex align-items-center mb-4">
                                <div class="step-icon mr-4">
                                    <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">2</span>
                                </div>
                                <div class="step-content">
                                    <h5 class="mb-1">AI phân tích</h5>
                                    <p class="mb-0">Thuật toán AI phân tích hình ảnh</p>
                                </div>
                            </div>
                            <div class="step d-flex align-items-center mb-4">
                                <div class="step-icon mr-4">
                                    <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">3</span>
                                </div>
                                <div class="step-content">
                                    <h5 class="mb-1">Nhận kết quả</h5>
                                    <p class="mb-0">Nhận kết quả ngay lập tức</p>
                                </div>
                            </div>
                            <div class="step d-flex align-items-center">
                                <div class="step-icon mr-4">
                                    <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">4</span>
                                </div>
                                <div class="step-content">
                                    <h5 class="mb-1">Theo dõi y tế</h5>
                                    <p class="mb-0">Tham khảo ý kiến bác sĩ da liễu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-widget rounded bg-white shadow-sm p-4">
                        <h4 class="mb-4">Liên hệ bác sĩ</h4>
                        <p>Dù AI có thể giúp phát hiện các vấn đề tiềm ẩn, việc gặp bác sĩ da liễu vẫn là điều quan trọng để có chẩn đoán và điều trị chính xác.</p>
                        <div class="text-center mt-4">
                            <a href="{{ route('doctors') }}" class="btn btn-main btn-block">
                                <i class="icofont-doctor-alt mr-2"></i>Xem bác sĩ của chúng tôi
                            </a>
                            <a href="{{ route('appoinment') }}" class="btn btn-outline-primary btn-block mt-2">
                                <i class="icofont-calendar mr-2"></i>Đặt lịch hẹn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = imagePreview.querySelector('img');
        const removeImage = document.getElementById('removeImage');
        const analyzeBtn = document.getElementById('analyzeBtn');

        // Kích hoạt khu vực tải lên 
        uploadArea.addEventListener('click', function() {
            imageInput.click();
        });

        // Xử lý kéo và thả
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function() {
                uploadArea.classList.add('highlight');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function() {
                uploadArea.classList.remove('highlight');
            }, false);
        });

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                imageInput.files = files;
                updateImageDisplay();
            }
        }

        // Xử lý tải lên hình ảnh
        imageInput.addEventListener('change', updateImageDisplay);

        function updateImageDisplay() {
            if (imageInput.files && imageInput.files[0]) {
                const file = imageInput.files[0];

                // Kiểm tra kích thước file (tối đa 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File quá lớn. Vui lòng chọn file nhỏ hơn 2MB.');
                    resetForm();
                    return;
                }

                // Kiểm tra loại file
                if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                    alert('Chỉ chấp nhận file hình ảnh định dạng JPG, JPEG hoặc PNG.');
                    resetForm();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                    uploadArea.classList.add('has-image');
                    analyzeBtn.disabled = false;
                }
                reader.readAsDataURL(file);
            }
        }

        // Xử lý xóa hình ảnh
        removeImage.addEventListener('click', resetForm);

        function resetForm() {
            imageInput.value = '';
            imagePreview.classList.add('d-none');
            uploadArea.classList.remove('has-image');
            analyzeBtn.disabled = true;
        }
    });
</script>

<style>
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-area:hover,
    .upload-area.highlight {
        border-color: #223a66;
        background-color: rgba(34, 58, 102, 0.05);
    }

    .upload-area.has-image {
        border-style: solid;
        background-color: #f8f9fa;
    }

    .tip-item {
        border-radius: 10px;
        background-color: #f8f9fa;
        height: 100%;
    }

    .ai-checkup-section {
        padding: 80px 0;
    }

    .results-card {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        background-color: #fff;
        height: 100%;
    }

    .step-icon span {
        font-weight: bold;
    }

    /* Animations */
    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        transform: translateX(5px);
    }

    @media (max-width: 767px) {
        .tip-item {
            margin-bottom: 15px;
        }

        .ai-checkup-section {
            padding: 40px 0;
        }
    }
</style>
@endsection