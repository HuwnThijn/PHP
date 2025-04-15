<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

@php
    use Illuminate\Support\Str;
@endphp

<html lang="en">

@include('user.partials.head')

<body id="top">

    @include('user.partials.header')

    <!-- Slider Start -->
    <div class="container py-4">
        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-6 d-flex">
                <div class="d-flex flex-column me-3">
                    <!-- Thumbnail images - using the main image for now -->
                    <img src="{{ asset($product->image) }}" alt="Thumbnail 1"
                        class="img-thumbnail mb-2" onclick="changeImage(this.src)">
                    
                    <img src="{{ asset($product->image) }}" alt="Thumbnail 2"
                        class="img-thumbnail mb-2" onclick="changeImage(this.src)">
                    
                    
                    <img src="{{ asset($product->image) }}" alt="Thumbnail 3"
                        class="img-thumbnail mb-2" onclick="changeImage(this.src)">
                
                </div>
                <div>
                    <img id="mainImage" src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                        class="img-fluid rounded shadow-lg">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <h2 class="fw-bold text-primary">{{ $product->name }}</h2>
                <div class="rating text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= ($product->rating ?? 4))
                            <i class="icofont-star text-warning"></i>
                        @else
                            <i class="icofont-star"></i>
                        @endif
                    @endfor
                    ({{ count($reviews) }} đánh giá)
                </div>
                
                <h4 class="mt-3">Thông tin sản phẩm:</h4>
                <p>{{ $product->description ?? 'Chưa có mô tả chi tiết.' }}</p>
                
                <!-- <h4>Danh mục:</h4>
                <p>{{ $product->category->name ?? 'Chưa phân loại' }}</p> -->
                
                <div class="product-price" style="color: red">
                    <p class="text-danger fw-bold fs-3">{{ number_format($product->price, 0, ',', '.') }}đ</p>
                </div>
                
                <p class="text-success fw-bold">Còn hàng</p>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-dark" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" class="form-control text-center"
                        style="width: 60px;">
                    <button class="btn btn-outline-dark" onclick="increaseQuantity()">+</button>
                </div>
                <div class="mt-3">
                    <button class="btn btn-danger btn-lg" id="addToCartBtn" 
                        data-product-id="{{ $product->id_cosmetic }}" 
                        data-cart-add-url="{{ route('cart.add') }}" 
                        data-cart-url="{{ route('user.cart') }}" 
                        data-login-url="{{ route('user.login') }}" 
                        data-csrf-token="{{ csrf_token() }}" 
                        data-is-logged-in="@auth 1 @else 0 @endauth">
                        Thêm vào giỏ hàng
                    </button>
                    <!-- <button class="btn btn-danger btn-lg" id="addToCartBtn" 
                        data-product-id="{{ $product->id_cosmetic }}" 
                        data-cart-add-url="{{ route('cart.add') }}" 
                        data-cart-url="{{ route('user.cart') }}" 
                        data-login-url="{{ route('user.login') }}" 
                        data-csrf-token="{{ csrf_token() }}" 
                        data-is-logged-in="@auth 1 @else 0 @endauth">
                        Mua ngay
                    </button> -->
                </div>

                <!-- Di chuyển phần So sánh & Yêu thích ra đây -->
                <div class="mt-3 compare-favorite">
                    <span><i class="icofont-exchange" style="color: blue;"></i> So sánh</span>
                    <span><i class="icofont-heart" style="color: red;"></i> Thêm vào yêu thích</span>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="product-info mt-3">
                    <p><strong>Mã sản phẩm:</strong> SP-{{ $product->id_cosmetic }}</p>
                    <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
                </div>
            </div>
        </div>

        <!-- Mô tả sản phẩm -->
        <div class="mt-4">
            <h4>Mô tả chi tiết</h4>
            <div class="product-description">
                {!! $product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.' !!}
            </div>
        </div>

        <!-- Product Reviews Section -->
        <div class="mt-5 product-reviews">
            <h4 class="mb-4">Đánh giá & Bình luận</h4>
            
            <!-- Display existing reviews -->
            <div class="existing-reviews mb-4">
                @if(isset($reviews) && count($reviews) > 0)
                    <div class="reviews-container">
                        @foreach($reviews as $review)
                            <div class="review-item mb-4 p-3 border rounded" id="review-{{ $review->id_review }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="user-info d-flex align-items-center">
                                        <div class="avatar-container me-3">
                                            @if($review->user->avatar && file_exists(public_path('storage/' . $review->user->avatar)))
                                                <div class="user-avatar" style="background-image: url('{{ asset('storage/' . $review->user->avatar) }}'); background-size: cover; background-position: center;"></div>
                                            @else
                                                <div class="user-avatar" style="background-image: url('{{ asset('user/theme/images/user-default.png') }}'); background-size: cover; background-position: center;"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $review->user->name }}</h5>
                                            <div class="rating text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="icofont-star text-warning"></i>
                                                    @else
                                                        <i class="icofont-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="review-date text-muted me-4">
                                            {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}
                                        </div>
                                        @auth
                                            @if(Auth::id() == $review->id_user)
                                                <a href="#" class="delete-review-btn text-danger ms-2" data-review-id="{{ $review->id_review }}">
                                                    <i class="icofont-trash"></i>
                                                </a>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                                <div class="review-content mt-3">
                                    <p>{{ $review->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!
                    </div>
                @endif
            </div>
            
            <!-- Review form for logged-in users -->
            @auth
                <div class="review-form-container border rounded p-4 bg-light">
                    <h5 class="mb-3">Đánh giá sản phẩm</h5>
                    <form id="reviewForm" action="{{ route('product.review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_cosmetic" value="{{ $product->id_cosmetic }}">
                        <input type="hidden" id="selected_rating" name="rating" value="">
                        
                        <div class="form-group mb-3">
                            <label for="rating">Đánh giá của bạn:</label>
                            <div class="rating-select">
                                <div class="star-rating">
                                    <div class="star" data-value="1"><i class="icofont-star"></i></div>
                                    <div class="star" data-value="2"><i class="icofont-star"></i></div>
                                    <div class="star" data-value="3"><i class="icofont-star"></i></div>
                                    <div class="star" data-value="4"><i class="icofont-star"></i></div>
                                    <div class="star" data-value="5"><i class="icofont-star"></i></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="comment">Bình luận:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                    </form>
                </div>
            @else
                <div class="alert alert-warning">
                    Vui lòng <a href="{{ route('user.login') }}">đăng nhập</a> để đánh giá sản phẩm này.
                </div>
            @endauth
        </div>

        <!-- Sản phẩm liên quan -->
        <div class="mt-4">
            <h4>Sản phẩm liên quan</h4>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 text-center">
                    <a href="{{ route('detailsp', ['slug' => Str::slug($relatedProduct->name)]) }}" class="product-link">
                        <img src="{{ asset($relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="img-fluid">
                        <h5 class="mt-2">{{ $relatedProduct->name }}</h5>
                        <p class="text-danger">
                            {{ number_format($relatedProduct->price, 0, ',', '.') }}đ
                        </p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .img-thumbnail {
            width: 90px;
            height: 90px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 10px;
        }

        .img-thumbnail:hover {
            border: 3px solid #007bff;
            transform: scale(1.1);
            transition: 0.3s;
        }

        .rating {
            font-size: 1.2em;
        }

        .btn-lg {
            padding: 10px 20px;
            border-radius: 10px;
        }

        .btn-outline-dark:hover {
            background-color: #000;
            color: white;
        }

        .product-info {
            font-size: 14px;
            color: #333;
        }

        .compare-favorite {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 13px;
        }

        .compare-favorite span {
            cursor: pointer;
        }
        
        .product-description {
            line-height: 1.6;
            color: #555;
        }
        
        /* Review styles */
        .review-item {
            background-color: #f9f9f9;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .review-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Sửa lại CSS cho star rating */
        .star-rating {
            display: flex;
            justify-content: flex-start;
            font-size: 24px;
        }
        
        .star-rating .star {
            cursor: pointer;
            padding: 0 5px;
            transition: color 0.2s ease;
        }
        
        /* Mặc định các sao có màu xám */
        .star-rating .star i {
            color: #ccc;
        }
        
        /* Sao được hover và được chọn có màu vàng */
        .star-rating .star.hovered i,
        .star-rating .star.selected i {
            color: #f8ce0b;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: inline-block;
            background-color: #f0f0f0;
            overflow: hidden;
        }
        
        /* Override any Bootstrap classes that might be affecting the img */
        img.user-avatar {
            max-width: none;
            max-height: none;
        }
        
        .default-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #223a66;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            border: 2px solid #e0e0e0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .avatar-container {
            min-width: 50px;
            min-height: 50px;
            margin-right: 15px;
        }
        
        .delete-review-btn {
            font-size: 18px;
            transition: all 0.2s ease;
        }
        
        .delete-review-btn:hover {
            transform: scale(1.2);
            color: #d9534f !important;
        }
        
        /* Animation for removing reviews */
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; height: 0; margin: 0; padding: 0; overflow: hidden; }
        }
        
        .review-fadeout {
            animation: fadeOut 0.5s ease forwards;
        }
        
        .reviews-container {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 8px;
            background-color: #fafafa;
            border: 1px solid #eee;
            padding: 15px 15px 15px 15px;
            scrollbar-width: thin;
            scrollbar-color: #c1c1c1 #f1f1f1;
        }
        
        /* Styling cho thanh cuộn trên trình duyệt webkit (Chrome, Safari, etc.) */
        .reviews-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .reviews-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .reviews-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .reviews-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Hiệu ứng hover mượt hơn */
        .star-rating:hover .star i {
            color: #ccc;
        }
        
        .star-rating:hover .star:hover i,
        .star-rating:hover .star:hover ~ .star i {
            color: #ccc;
        }
        
        .star-rating:hover .star:hover i {
            color: #f8ce0b;
        }
        
        .star-rating:hover .star:not(:hover) i {
            /* Giữ nguyên màu khi hover ngang qua */
            transition: color 0.1s ease; 
        }

        /* Khi không hover, hiển thị các sao đã chọn */
        .star-rating:not(:hover) .star.selected i {
            color: #f8ce0b;
        }
    </style>

    <!-- footer Start -->
    @include('user.partials.footer')
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/product-detail.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Log avatar paths for debugging
            document.querySelectorAll('.user-avatar').forEach(img => {
                console.log('Avatar path:', img.src);
            });
            
            // Ensure product links work properly
            document.querySelectorAll('.product-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = this.getAttribute('href');
                });
            });
            
            // Star rating functionality
            const stars = document.querySelectorAll('.star-rating .star');
            const selectedRatingInput = document.getElementById('selected_rating');
            
            // Add hover event to stars for left-to-right hover effect
            stars.forEach(star => {
                star.addEventListener('mouseenter', function() {
                    const hoverValue = parseInt(this.getAttribute('data-value'));
                    
                    // Update hover state for all stars
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        if (starValue <= hoverValue) {
                            s.classList.add('hovered');
                        } else {
                            s.classList.remove('hovered');
                        }
                    });
                });
                
                star.addEventListener('mouseleave', function() {
                    // Remove hover state when mouse leaves
                    stars.forEach(s => {
                        s.classList.remove('hovered');
                    });
                });
            });
            
            // Star rating container also needs mouseleave handler
            const starRating = document.querySelector('.star-rating');
            if (starRating) {
                starRating.addEventListener('mouseleave', function() {
                    stars.forEach(s => {
                        s.classList.remove('hovered');
                    });
                });
            }
            
            // Add click event to stars
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    selectedRatingInput.value = value;
                    
                    // Update visual state
                    stars.forEach(s => {
                        s.classList.remove('selected');
                    });
                    
                    // Set selected class for this star and all stars to the left
                    let current = this;
                    while (current) {
                        current.classList.add('selected');
                        current = current.previousElementSibling;
                    }
                    
                    console.log('Selected rating:', value);
                });
            });
            
            // Review form handling
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Verify the selected rating before submitting
                    if (!selectedRatingInput.value) {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Vui lòng chọn số sao đánh giá.',
                            icon: 'error',
                            confirmButtonText: 'Đóng'
                        });
                        return;
                    }
                    
                    console.log('Submitting rating:', selectedRatingInput.value);
                    
                    const formData = new FormData(this);
                    
                    fetch(this.getAttribute('action'), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Cảm ơn bạn đã đánh giá sản phẩm.',
                                icon: 'success',
                                confirmButtonText: 'Đóng'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: data.message || 'Đã xảy ra lỗi khi gửi đánh giá.',
                                icon: 'error',
                                confirmButtonText: 'Đóng'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Đã xảy ra lỗi khi gửi đánh giá.',
                            icon: 'error',
                            confirmButtonText: 'Đóng'
                        });
                    });
                });
            }
            
            // Hide reviews initially (show only the first 3)
            const reviewItems = document.querySelectorAll('.review-item');
            if (reviewItems.length > 3) {
                console.log(`Hiển thị tất cả ${reviewItems.length} đánh giá trong thanh cuộn`);
            }
            
            // Delete review functionality
            document.querySelectorAll('.delete-review-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const reviewId = this.getAttribute('data-review-id');
                    
                    Swal.fire({
                        title: 'Xác nhận xóa?',
                        text: 'Bạn có chắc chắn muốn xóa đánh giá này không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                        backdrop: `rgba(0,0,0,0.4)`,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Add fadeout effect before sending the request
                            const reviewElement = document.getElementById(`review-${reviewId}`);
                            if (reviewElement) {
                                reviewElement.classList.add('review-fadeout');
                            }
                            
                            // Send delete request to server
                            fetch('{{ route('product.review.delete') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    id_review: reviewId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the review from DOM after animation completes
                                    setTimeout(() => {
                                        if (reviewElement) {
                                            reviewElement.remove();
                                        }
                                        
                                        Swal.fire(
                                            'Đã xóa!',
                                            'Đánh giá của bạn đã được xóa.',
                                            'success'
                                        );
                                        
                                        // If no reviews left, show the "no reviews" message
                                        const remainingReviews = document.querySelectorAll('.review-item').length;
                                        if (remainingReviews === 0) {
                                            const noReviewsMessage = document.createElement('div');
                                            noReviewsMessage.className = 'alert alert-info';
                                            noReviewsMessage.textContent = 'Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!';
                                            document.querySelector('.existing-reviews').appendChild(noReviewsMessage);
                                        }
                                    }, 500);
                                } else {
                                    // If error, remove the fadeout class
                                    if (reviewElement) {
                                        reviewElement.classList.remove('review-fadeout');
                                    }
                                    
                                    Swal.fire(
                                        'Lỗi!',
                                        data.message || 'Không thể xóa đánh giá.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                // If error, remove the fadeout class
                                if (reviewElement) {
                                    reviewElement.classList.remove('review-fadeout');
                                }
                                
                                Swal.fire(
                                    'Lỗi!',
                                    'Đã xảy ra lỗi khi xóa đánh giá.',
                                    'error'
                                );
                            });
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>
