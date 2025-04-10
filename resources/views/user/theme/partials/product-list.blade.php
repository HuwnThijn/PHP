@php
    use Illuminate\Support\Str;
@endphp

@if($products->isEmpty())
    <div class="col-12 text-center py-5">
        <h4>Không tìm thấy sản phẩm nào</h4>
        <p>Vui lòng thử lại với bộ lọc khác</p>
    </div>
@else
    <div class="row product-container">
        @foreach($products as $product)
            <div class="{{ $view == 'grid' ? 'col-lg-3 col-md-6' : 'col-lg-6 col-md-6' }} mb-4">
                <div class="product-item">
                    <div class="product-thumb">
                        <img src="{{ $product->image ? asset($product->image) : asset('user/theme/images/product-placeholder.jpg') }}" 
                            alt="{{ $product->name }}" class="img-fluid">
                        <div class="product-hover-overlay" style="color: red">
                            <a href="{{ route('detailsp', ['slug' => Str::slug($product->name)]) }}" class="product-icon">
                                <i class="icofont-eye"></i>
                            </a>
                            <a href="#" class="product-icon" onclick="event.preventDefault(); addToCart({{ $product->id_cosmetic }})">
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
                           onclick="event.preventDefault(); addToCart({{ $product->id_cosmetic }})"
                           data-product-id="{{ $product->id_cosmetic }}">CHỌN MUA</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endif 

<style>
.product-item-list {
    display: flex;
    border: 1px solid #eee;
    margin-bottom: 20px;
    padding: 15px;
}

.product-item-list .product-thumb {
    width: 200px;
    padding-top: 200px;
    margin-right: 20px;
}

.product-item-list .product-content {
    flex: 1;
}
</style>