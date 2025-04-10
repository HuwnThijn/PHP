@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">

@include('user.partials.head')

<body id="top">

    @include('user.partials.header')

    <!-- Notification container -->
    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <section class="page-title bg-1">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block text-center">
                        <span class="text-white">Our Products</span>
                        <h1 class="text-capitalize mb-5 text-lg">O2Skin Store</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section store">
        <div class="container">
            <div class="row">
                <!-- Sidebar with filters -->
                <div class="col-lg-3 col-md-4">
                    <div class="sidebar-wrap">
                        <div class="sidebar-widget mb-4">
                            <h5 class="mb-3">Bộ lọc nâng cao</h5>
                            <form action="{{ route('store') }}" method="GET" id="filter-form" onsubmit="return validateSearchForm()">
                                <input type="hidden" name="sort" value="{{ $sort ?? 'default' }}">
                                <input type="hidden" name="view" value="{{ $view ?? 'grid' }}">

                                <!-- Search box -->
                                <div class="form-group mb-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" id="search-input"
                                            placeholder="Tìm kiếm sản phẩm..." value="{{ $search ?? '' }}"
                                            autocomplete="off">
                                        <div class="input-group-append">
                                            <button class="btn btn-main" type="submit">
                                                <i class="icofont-search-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="autosuggest" class="autosuggest-text"></div>
                                    <div id="search-results" class="search-results-dropdown d-none">
                                        <!-- Search results will appear here -->
                                    </div>
                                </div>

                                <!-- Brand/Category filter -->
                                <div class="widget-category mb-4">
                                    <h5 class="mb-3">Thương hiệu</h5>
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="category-all" name="category" value=""
                                            {{ !isset($category_id) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="category-all">Tất cả</label>
                                    </div>
                                    @foreach($categories as $category)
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" class="custom-control-input" id="category-{{ $category->id_category }}"
                                            name="category" value="{{ $category->id_category }}"
                                            {{ isset($category_id) && $category_id == $category->id_category ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="category-{{ $category->id_category }}">{{ $category->name }}</label>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Price filter -->
                                <div class="widget-price-filter mb-4">
                                    <h5 class="mb-3">Giá bán</h5>
                                    <div class="price-range">
                                        <div class="btn-group-vertical w-100">
                                            <button type="button" class="btn btn-outline-secondary mb-2 text-start {{ isset($filters['min_price']) && $filters['min_price'] == 0 && isset($filters['max_price']) && $filters['max_price'] == 100000 ? 'active' : '' }}"
                                                onclick="setPriceRange(0, 100000)">
                                                Dưới 100.000đ
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mb-2 text-start {{ isset($filters['min_price']) && $filters['min_price'] == 100000 && isset($filters['max_price']) && $filters['max_price'] == 300000 ? 'active' : '' }}"
                                                onclick="setPriceRange(100000, 300000)">
                                                100.000đ đến 300.000đ
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mb-2 text-start {{ isset($filters['min_price']) && $filters['min_price'] == 300000 && isset($filters['max_price']) && $filters['max_price'] == 500000 ? 'active' : '' }}"
                                                onclick="setPriceRange(300000, 500000)">
                                                300.000đ đến 500.000đ
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary mb-2 text-start {{ isset($filters['min_price']) && $filters['min_price'] == 500000 && !isset($filters['max_price']) ? 'active' : '' }}"
                                                onclick="setPriceRange(500000, null)">
                                                Trên 500.000đ
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs for price range -->
                                <input type="hidden" name="min_price" id="min-price" value="{{ $filters['min_price'] ?? '' }}">
                                <input type="hidden" name="max_price" id="max-price" value="{{ $filters['max_price'] ?? '' }}">

                                {{-- <button type="submit" class="btn btn-main btn-block">Áp dụng</button>
                                <a href="{{ route('store') }}" class="btn btn-outline-secondary btn-block mt-2">Xóa bộ lọc</a> --}}
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Product listing -->
                <div class="col-lg-9 col-md-8">
                    <div class="product-section">
                        <div class="sort-view-container">
                            <div class="row mb-4 align-items-center">
                                <div class="col-12 mb-3">
                                    <!-- Active filters -->
                                    <div class="active-filters d-flex flex-wrap align-items-center">
                                        <span class="mr-2">Lọc theo ({{ count(array_filter([!empty($search), !empty($category_id), !empty($min_price)])) }})</span>
                                        @if(!empty($search))
                                        <div class="filter-tag">
                                            <span class="mr-1">{{ $search }}</span>
                                            <a href="{{ route('store', array_merge(request()->query(), ['search' => ''])) }}" class="remove-filter">×</a>
                                        </div>
                                        @endif
                                        @if(!empty($category_id))
                                        <div class="filter-tag">
                                            <span class="mr-1">{{ $categories->where('id_category', $category_id)->first()->name }}</span>
                                            <a href="{{ route('store', array_merge(request()->query(), ['category' => ''])) }}" class="remove-filter">×</a>
                                        </div>
                                        @endif
                                        @if(!empty($min_price) || !empty($max_price))
                                        <div class="filter-tag">
                                            <span class="mr-1">
                                                @if(!empty($min_price) && !empty($max_price))
                                                {{ number_format($min_price, 0, ',', '.') }}đ đến {{ number_format($max_price, 0, ',', '.') }}đ
                                                @elseif(!empty($min_price))
                                                Trên {{ number_format($min_price, 0, ',', '.') }}đ
                                                @else
                                                Dưới {{ number_format($max_price, 0, ',', '.') }}đ
                                                @endif
                                            </span>
                                            <a href="{{ route('store', array_merge(request()->query(), ['min_price' => '', 'max_price' => ''])) }}" class="remove-filter">×</a>
                                        </div>
                                        @endif
                                        @if(!empty($search) || !empty($category_id) || !empty($min_price))
                                        <a href="{{ route('store') }}" class="filter-tag remove-all">
                                            Xóa tất cả
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-md-0">Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} của {{ $products->total() ?? 0 }} sản phẩm</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-md-end">
                                        <!-- Sort options -->
                                        <div class="sort-options mr-3">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn {{ ($sort ?? '') == 'bestseller' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateSort('bestseller')">Bán chạy</button>
                                                <button type="button" class="btn {{ ($sort ?? '') == 'price_asc' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateSort('price_asc')">Giá thấp</button>
                                                <button type="button" class="btn {{ ($sort ?? '') == 'price_desc' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateSort('price_desc')">Giá cao</button>
                                                <button type="button" class="btn {{ ($sort ?? '') == 'name_asc' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateSort('name_asc')">A-Z</button>
                                                <button type="button" class="btn {{ ($sort ?? '') == 'name_desc' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateSort('name_desc')">Z-A</button>
                                            </div>
                                        </div>

                                        <!-- View options -->
                                        <div class="view-options">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn {{ ($view ?? 'grid') == 'grid' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateView('grid')" title="Lưới">
                                                    <i class="icofont-justify-all"></i>
                                                </button>
                                                <button type="button" class="btn {{ ($view ?? 'grid') == 'list' ? 'btn-main' : 'btn-outline-secondary' }}"
                                                    onclick="updateView('list')" title="Danh sách">
                                                    <i class="icofont-listine-dots"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="product-listing-area">
                            <!-- Products grid/list view -->
                            <div class="row product-container">
                                @if($products->isEmpty())
                                <div class="col-12 text-center py-5">
                                    <h4>Không tìm thấy sản phẩm nào</h4>
                                    <p>Vui lòng thử lại với bộ lọc khác</p>
                                </div>
                                @else
                                @php
                                $itemsToShow = ($view ?? 'grid') == 'grid' ? 8 : 4; // Hiển thị 8 sản phẩm (2 hàng) cho chế độ lưới, 4 sản phẩm cho chế độ danh sách
                                $hasMoreItems = $products->count() > $itemsToShow;
                                $visibleProducts = $products->take($itemsToShow);
                                $hiddenProducts = $products->slice($itemsToShow);
                                @endphp

                                @foreach($visibleProducts as $product)
                                <div class="{{ ($view ?? 'grid') == 'grid' ? 'col-lg-3 col-md-6' : 'col-lg-6 col-md-6' }} mb-4">
                                    <div class="product-item">
                                        <div class="product-thumb">
                                            <img src="{{ $product->image ? asset($product->image) : asset('user/theme/images/product-placeholder.jpg') }}"
                                                alt="{{ $product->name }}" class="img-fluid">
                                            <div class="product-hover-overlay">
                                                <a href="{{ route('detailsp', ['slug' => Str::slug($product->name)]) }}" class="product-icon">
                                                    <i class="icofont-eye"></i>
                                                </a>
                                                <a href="#" class="product-icon" onclick="event.preventDefault(); addToCart('{{ $product->id_cosmetic }}')">
                                                    <i class="icofont-shopping-cart"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h4 class="mb-2 product-title">
                                                <a href="{{ route('detailsp', ['slug' => Str::slug($product->name)]) }}">{{ $product->name }}</a>
                                            </h4>
                                            <p class="product-category">{{ $product->category->name }}</p>
                                            <div class="product-price" style="color: red">
                                                <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                            </div>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <=($product->rating ?? 4))
                                                    <i class="icofont-star text-warning"></i>
                                                    @else
                                                    <i class="icofont-star"></i>
                                                    @endif
                                                    @endfor
                                            </div>
                                            <a href="#" class="btn btn-main btn-small btn-round-full add-to-cart-btn" 
                                               onclick="event.preventDefault(); addToCart('{{ $product->id_cosmetic }}')"
                                               data-product-id="{{ $product->id_cosmetic }}">CHỌN MUA</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if($hasMoreItems)
                                <div id="hidden-products" style="display: none;" class="row w-100">
                                    @foreach($hiddenProducts as $product)
                                    <div class="{{ ($view ?? 'grid') == 'grid' ? 'col-lg-3 col-md-6' : 'col-lg-6 col-md-6' }} mb-4">
                                        <div class="product-item">
                                            <div class="product-thumb">
                                                <img src="{{ $product->image ? asset($product->image) : asset('user/theme/images/product-placeholder.jpg') }}"
                                                    alt="{{ $product->name }}" class="img-fluid">
                                                <div class="product-hover-overlay">
                                                    <a href="{{ route('detailsp', ['slug' => Str::slug($product->name)]) }}" class="product-icon">
                                                        <i class="icofont-eye"></i>
                                                    </a>
                                                    <a href="#" class="product-icon" onclick="event.preventDefault(); addToCart('{{ $product->id_cosmetic }}')">
                                                        <i class="icofont-shopping-cart"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <h4 class="mb-2 product-title">
                                                    <a href="{{ route('detailsp', ['slug' => Str::slug($product->name)]) }}">{{ $product->name }}</a>
                                                </h4>
                                                <p class="product-category">{{ $product->category->name }}</p>
                                                <div class="product-price" style="color: red">
                                                    <span>{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                                </div>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= ($product->rating ?? 4))
                                                            <i class="icofont-star text-warning"></i>
                                                        @else
                                                            <i class="icofont-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <a href="#" class="btn btn-main btn-small btn-round-full add-to-cart-btn" 
                                                   onclick="event.preventDefault(); addToCart('{{ $product->id_cosmetic }}')"
                                                   data-product-id="{{ $product->id_cosmetic }}">CHỌN MUA</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-12 text-center mb-5">
                                    <button id="load-more" class="btn btn-outline-primary">Hiện thêm...</button>
                                </div>
                                @endif
                                @endif
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="pagination mt-4 justify-content-center">
                                        {{ $products->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('user.partials.footer')

    <!-- Essential Scripts -->
    <script src="{{ asset('user/theme/plugins/jquery/jquery.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/slick-carousel/slick/slick.min.js') }}"></script>
    <script src="{{ asset('user/theme/plugins/shuffle/shuffle.min.js') }}"></script>
    <script src="{{ asset('user/theme/js/script.js') }}"></script>

    <!-- Store page specific scripts -->
    <script>
        // Hàm hiển thị thông báo đẹp với SweetAlert2
        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Hàm cập nhật số lượng sản phẩm trong giỏ hàng
        function updateCartCount(count) {
            // Cập nhật số lượng hiển thị trên icon giỏ hàng (nếu có)
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
            }
        }

        // Update sort parameter with AJAX
        function updateSort(value) {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', value);
            fetchProducts(currentUrl.toString());

            // Update active button state
            $('.sort-options .btn').removeClass('btn-main').addClass('btn-outline-secondary');
            $(`.sort-options .btn[onclick*="${value}"]`).addClass('btn-main').removeClass('btn-outline-secondary');
        }

        // Update view parameter with AJAX
        function updateView(value) {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('view', value);
            fetchProducts(currentUrl.toString());

            // Update active button state
            $('.view-options .btn').removeClass('btn-main').addClass('btn-outline-secondary');
            $(`.view-options .btn[onclick*="${value}"]`).addClass('btn-main').removeClass('btn-outline-secondary');
        }

        // Set price range with AJAX
        function setPriceRange(min, max) {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('min_price', min);
            currentUrl.searchParams.set('max_price', max || '');

            // Update hidden inputs
            $('#min-price').val(min);
            $('#max-price').val(max || '');

            fetchProducts(currentUrl.toString());

            // Update active button state
            $('.price-range .btn').removeClass('active');
            if (max === null) {
                $(`.price-range .btn[onclick*="setPriceRange(${min}, null)"]`).addClass('active');
            } else {
                $(`.price-range .btn[onclick*="setPriceRange(${min}, ${max})"]`).addClass('active');
            }
        }

        // Main function to fetch products via AJAX
        function fetchProducts(url, pushState = true) {
            // Show loading state
            $('.product-listing-area').addClass('loading');

            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        // Update product list
                        $('.product-listing-area').html(response.html);

                        // Update URL without page reload if needed
                        if (pushState) {
                            window.history.pushState({}, '', url);
                        }

                        // Update active filters if provided
                        if (response.filters) {
                            updateActiveFilters(response.filters);
                        }

                        // Reinitialize event handlers
                        initializeEventHandlers();
                    } else {
                        // Handle error response
                        alert(response.message || 'Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại.');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching products:', xhr);
                    alert('Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại.');
                },
                complete: function() {
                    // Remove loading state
                    $('.product-listing-area').removeClass('loading');
                }
            });
        }

        // Function to update active filters display
        function updateActiveFilters(filters) {
            let filterCount = filters.active_count || 0;
            let filterHtml = '<span class="mr-2">Lọc theo (' + filterCount + ')</span>';

            if (filters.search) {
                filterHtml += `
                    <div class="filter-tag" data-filter-type="search">
                        <span class="mr-1">${filters.search}</span>
                        <a href="#" class="remove-filter">×</a>
                    </div>`;
            }

            if (filters.category_id) {
                const categoryName = $(`#category-${filters.category_id}`).next('label').text();
                filterHtml += `
                    <div class="filter-tag" data-filter-type="category">
                        <span class="mr-1">${categoryName}</span>
                        <a href="#" class="remove-filter">×</a>
                    </div>`;
            }

            if (filters.min_price || filters.max_price) {
                let priceText = '';
                if (filters.min_price && filters.max_price) {
                    priceText = `${new Intl.NumberFormat('vi-VN').format(filters.min_price)}đ đến ${new Intl.NumberFormat('vi-VN').format(filters.max_price)}đ`;
                } else if (filters.min_price) {
                    priceText = `Trên ${new Intl.NumberFormat('vi-VN').format(filters.min_price)}đ`;
                } else if (filters.max_price) {
                    priceText = `Dưới ${new Intl.NumberFormat('vi-VN').format(filters.max_price)}đ`;
                }

                if (priceText) {
                    filterHtml += `
                        <div class="filter-tag" data-filter-type="price">
                            <span class="mr-1">${priceText}</span>
                            <a href="#" class="remove-filter">×</a>
                        </div>`;
                }
            }

            // Add "Clear all" button if there are any filters
            if (filterCount > 0) {
                filterHtml += `
                    <a href="#" class="filter-tag remove-all">
                        Xóa tất cả
                    </a>`;
            }

            $('.active-filters').html(filterHtml);
        }

        // Initialize event handlers
        function initializeEventHandlers() {
            // Handle "Show more" button
            $('#load-more').off('click').on('click', function() {
                $('#hidden-products').slideDown();
                $(this).hide();
            });

            // Handle add to cart buttons
            $('.add-to-cart').off('click').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                addToCart(productId);
            });

            // Handle "Chọn mua" button click
            $('.add-to-cart-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                addToCart(productId);
            });

            // Handle pagination links
            $('.pagination a').off('click').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) {
                    fetchProducts(url);
                    // Scroll to top of product list
                    $('html, body').animate({
                        scrollTop: $('.product-listing-area').offset().top - 100
                    }, 200);
                }
            });
        }

        // Function to add product to cart
        function addToCart(productId) {
            $.ajax({
                url: '{{ route('cart.add') }}',
                type: 'POST',
                data: {
                    "product_id": productId,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Hiển thị thông báo thành công
                        showToast(response.message, 'success');

                        // Cập nhật số lượng sản phẩm trong giỏ hàng
                        updateCartCount(response.cart_count);

                        // Hiệu ứng animation cho nút đã nhấn
                        $('.add-to-cart-btn[data-product-id="' + productId + '"]').addClass('added').delay(1000).queue(function() {
                            $(this).removeClass('added').dequeue();
                        });
                    } else {
                        showToast(response.message || 'Không thể thêm sản phẩm vào giỏ hàng', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error adding to cart:', xhr);
                    // Redirect to login if unauthorized (status code 401)
                    if (xhr.status === 401) {
                        Swal.fire({
                            title: 'Bạn cần đăng nhập',
                            text: 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng. Bạn có muốn đăng nhập ngay bây giờ không?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Đăng nhập ngay',
                            cancelButtonText: 'Không, để sau'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(window.location.href);
                            }
                        });
                    } else {
                        showToast('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.', 'error');
                    }
                },
                complete: function() {
                    // Do nothing
                }
            });
        }

        $(document).ready(function() {
            // Initialize event handlers
            initializeEventHandlers();

            // Handle filter form submission
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                let currentUrl = new URL(window.location.href);
                const newUrl = currentUrl.origin + currentUrl.pathname + '?' + formData;
                fetchProducts(newUrl);
            });

            // Handle category radio changes
            $('input[name="category"]').on('change', function() {
                $('#filter-form').submit();
            });

            // Handle remove filter clicks
            $(document).on('click', '.remove-filter', function(e) {
                e.preventDefault();
                let currentUrl = new URL(window.location.href);
                const filterType = $(this).closest('.filter-tag').data('filter-type');

                switch (filterType) {
                    case 'search':
                        currentUrl.searchParams.delete('search');
                        $('#search-input').val('');
                        break;
                    case 'category':
                        currentUrl.searchParams.delete('category');
                        $('input[name="category"][value=""]').prop('checked', true);
                        break;
                    case 'price':
                        currentUrl.searchParams.delete('min_price');
                        currentUrl.searchParams.delete('max_price');
                        $('#min-price').val('');
                        $('#max-price').val('');
                        $('.price-range .btn').removeClass('active');
                        break;
                }

                fetchProducts(currentUrl.toString());
            });

            // Handle remove all filters
            $(document).on('click', '.remove-all', function(e) {
                e.preventDefault();
                let currentUrl = new URL(window.location.href);
                const baseUrl = currentUrl.origin + currentUrl.pathname;

                // Reset all form inputs
                $('#filter-form')[0].reset();
                $('#search-input').val('');
                $('#min-price').val('');
                $('#max-price').val('');
                $('input[name="category"][value=""]').prop('checked', true);
                $('.price-range .btn').removeClass('active');

                fetchProducts(baseUrl);
            });
        });

        // Search autocomplete
        $(document).ready(function() {
            const searchInput = $('#search-input');
            const searchResults = $('#search-results');
            let typingTimer;

            searchInput.on('input', function() {
                clearTimeout(typingTimer);
                const query = $(this).val().trim();

                if (query.length >= 2) {
                    typingTimer = setTimeout(function() {
                        $.ajax({
                            url: "{{ route('store') }}",
                            type: "GET",
                            data: {
                                query: query
                            },
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            success: function(response) {
                                if (response.success && response.suggestions.length > 0) {
                                    let html = '<div class="list-group">';
                                    response.suggestions.forEach(function(item) {
                                        html += `
                                            <a href="#" class="list-group-item list-group-item-action" 
                                               onclick="selectSearchItem('${item.name}')">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>${item.name}</span>
                                                    <span class="text-primary">${new Intl.NumberFormat('vi-VN').format(item.price)}đ</span>
                                                </div>
                                            </a>`;
                                    });
                                    html += '</div>';
                                    searchResults.html(html).removeClass('d-none');
                                } else {
                                    searchResults.addClass('d-none');
                                }
                            }
                        });
                    }, 300);
                } else {
                    searchResults.addClass('d-none');
                }
            });

            // Close search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-container').length) {
                    searchResults.addClass('d-none');
                }
            });
        });

        function selectSearchItem(text) {
            $('#search-input').val(text);
            $('#search-results').addClass('d-none');
            $('#filter-form').submit();
        }
    </script>

    <style>
        /* Store page layout */
        .store {
            padding: 40px 0;
            position: relative;
            min-height: calc(100vh - 200px);
        }

        /* Fixed height container for main content */
        .store .container {
            height: 100%;
        }

        /* Sidebar styles - no scroll */
        .sidebar-wrap {
            position: sticky;
            top: 0;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        /* Product section layout */
        .product-section {
            height: calc(100vh - 200px);
            display: flex;
            flex-direction: column;
        }

        /* Fixed sort and view options */
        .sort-view-container {
            position: sticky;
            top: 0;
            background: #fff;
            padding: 15px 0;
            border-bottom: 1px solid #dee2e6;
            z-index: 99;
        }

        /* Scrollable product container */
        .product-listing-area {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .product-listing-area::-webkit-scrollbar {
            display: none;
        }

        /* Product item styles */
        .product-item {
            border: 1px solid #eee;
            border-radius: 5px;
            transition: all 0.3s ease;
            height: 100%;
            background: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-item:hover {
            border-color: #223a66;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .product-thumb {
            position: relative;
            overflow: hidden;
            padding-top: 100%;
            /* 1:1 Aspect Ratio */
        }

        .product-thumb img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-item:hover .product-thumb img {
            transform: scale(1.05);
        }

        .product-hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .product-item:hover .product-hover-overlay {
            opacity: 1;
        }

        .product-icon {
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 5px;
            color: #223a66;
            transition: all 0.3s ease;
        }

        .product-icon:hover {
            background: #223a66;
            color: #fff;
        }

        .product-content {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-content-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .product-title {
            height: 48px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-clamp: 2;
        }

        .product-category {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .product-price {
            margin-bottom: 10px;
            font-weight: 600;
            color: #223a66;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            margin-right: 10px;
        }

        .bage {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e12454;
            color: #fff;
            padding: 2px 10px;
            font-size: 12px;
            border-radius: 3px;
            z-index: 1;
        }

        .text-center {
            margin-top: auto;
        }

        /* Grid layout fixes */
        .product-container {
            display: flex;
            flex-wrap: wrap;
        }

        .product-container>div {
            margin-bottom: 30px;
        }

        /* Sort buttons styles */
        .sort-options {
            display: flex;
            align-items: center;
            margin-right: 15px;
            flex-wrap: wrap;
        }

        .sort-options .btn-group {
            display: flex;
            flex-wrap: wrap;
        }

        .sort-options .btn,
        .view-options .btn {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 0;
            border: 1px solid #dee2e6;
            background-color: #fff;
            color: #495057;
            margin-right: -1px;
            white-space: nowrap;
        }

        .sort-options .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .sort-options .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
            margin-right: 0;
        }

        .sort-options .btn:hover,
        .view-options .btn:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #223a66;
            z-index: 1;
        }

        .sort-options .btn.btn-main,
        .view-options .btn.btn-main {
            background-color: #223a66;
            border-color: #223a66;
            color: #fff;
            z-index: 2;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .store {
                padding: 20px 0;
            }

            .product-section {
                height: auto;
            }

            .product-listing-area {
                overflow-y: visible;
            }

            .sort-options {
                margin-bottom: 10px;
            }

            .sort-options .btn {
                padding: 6px 12px;
                font-size: 13px;
            }
        }

        /* Active filters styles */
        .active-filters {
            margin-bottom: 15px;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            padding: 4px 12px;
            margin-right: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #495057;
        }

        .filter-tag.remove-all {
            background-color: transparent;
            color: #dc3545;
            text-decoration: none;
        }

        .filter-tag.remove-all:hover {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .remove-filter {
            color: #6c757d;
            margin-left: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.2rem;
            line-height: 1;
        }

        .remove-filter:hover {
            color: #dc3545;
            text-decoration: none;
        }

        /* Cart notification styles */
        .cart-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ffffff;
            color: #495057;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .cart-notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Thêm CSS cho trang store */
        .product-card {
            border-radius: 8px;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .price-range-filter .price-range-field {
            width: 45%;
            min-width: 0;
        }

        .btn-filter {
            min-width: 100px;
        }

        .filter-tag {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            margin: 0.2rem;
            background: #f8f9fa;
            border-radius: 20px;
            font-size: 0.8rem;
            border: 1px solid #ebebeb;
        }

        .filter-tag .remove-filter {
            margin-left: 5px;
            color: #dc3545;
            cursor: pointer;
        }

        .text-line-through {
            text-decoration: line-through;
        }

        .add-to-cart-btn {
            transition: all 0.3s;
        }

        .add-to-cart-btn:hover {
            transform: scale(1.05);
        }

        .add-to-cart-btn.added {
            background-color: #28a745 !important;
            color: white !important;
            transform: scale(1.1);
            animation: pulse 0.5s;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
        }

            50% {
                transform: scale(1.2);
        }

            100% {
                transform: scale(1.1);
            }
        }

        /* Custom SweetAlert2 styles */
        .swal2-popup {
            font-size: 1rem;
            border-radius: 15px;
        }

        .swal2-title {
            font-size: 1.4rem;
        }

        .swal2-toast {
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .swal2-success {
            color: #28a745;
        }

        .swal2-error {
            color: #dc3545;
        }
    </style>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>