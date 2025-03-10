<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="en">

@include('user.partials.head')

<body id="top">

    @include('user.partials.header')

    {{-- <!-- Slider Start -->
    <div class="container py-4">
        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-6 d-flex">
                <div class="d-flex flex-column me-3">
                    <img src="{{ asset('user/theme/images/team/2.jpg') }}" alt="Thumbnail 1"
                        class="img-thumbnail mb-2" onclick="changeImage(this)">
                    <img src="{{ asset('user/theme/images/team/3.jpg') }}" alt="Thumbnail 2"
                        class="img-thumbnail mb-2" onclick="changeImage(this)">
                    <img src="{{ asset('user/theme/images/team/4.jpg') }}" alt="Thumbnail 3"
                        class="img-thumbnail mb-2" onclick="changeImage(this)">
                </div>
                <div>
                    <img id="mainImage" src="{{ asset('user/theme/images/team/1.jpg') }}" alt="Sản phẩm chính"
                        class="img-fluid rounded shadow-lg">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <h2 class="fw-bold text-primary">Combo làm sạch, trị mụn, ngừa thâm sẹo Lipocumin</h2>
                <div class="rating text-warning">★★★★☆ (0 đánh giá)</div>
                <h4 class="mt-3">Bộ sản phẩm bao gồm:</h4>
                <ul>
                    <li>Sữa rửa mặt tẩy tế bào chết Clean 2in1 (Tuýp 100g)</li>
                    <li>Kem hỗ trợ trị mụn, mờ thâm Lipocumin (Tuýp 20g)</li>
                </ul>
                <h4>Công dụng:</h4>
                <p>Loại bỏ bã nhờn, dầu thừa, giúp da sáng mịn hơn.</p>
                <p class="text-danger fw-bold fs-3">520,000 đ <small
                        class="text-muted text-decoration-line-through">570,000 đ</small></p>
                <p class="text-success fw-bold">Còn hàng</p>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-dark" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" class="form-control text-center mx-2"
                        style="width: 60px;">
                    <button class="btn btn-outline-dark" onclick="increaseQuantity()">+</button>
                </div>
                <div class="mt-3">
                    <button class="btn btn-dark btn-lg">Thêm vào giỏ hàng</button>
                    <button class="btn btn-danger btn-lg">Mua ngay</button>
                </div>
            </div>
            <div class="mt-4">
                <div class="product-info">
                    <div class="compare-favorite">
                        <span>🔄 So sánh</span>
                        <span>❤️ Thêm vào yêu thích</span>
                    </div>
                    <p><strong>Mã sản phẩm:</strong> SF-2443-YF9Q</p>
                    <p><strong>Danh mục:</strong> Kem hỗ trợ trị mụn, Tẩy trang - Sữa rửa mặt</p>
                </div>
            </div>
            <!-- Mô tả sản phẩm -->
            <div class="mt-4">
                <h4>Mô tả</h4>
                <p>Sữa rửa mặt tẩy tế bào chết Clean 2in1 kết hợp công thức Sâm Nghệ đỏ và các thành phần AHA, BHA,
                    PHA...
                </p>
                <p>Kem hỗ trợ trị mụn, mờ thâm Lipocumin giúp giảm mụn, dưỡng ẩm da, làm đều màu da...</p>
            </div>

            <!-- Sản phẩm liên quan -->
            <div class="mt-4">
                <h4>Sản phẩm liên quan</h4>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('user/theme/images/team/1.jpg') }}" alt="Clean 2in1" class="img-fluid">
                        <p>Sữa rửa mặt tẩy tế bào chết Clean 2in1</p>
                        <p class="text-danger">254,000 đ</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('user/theme/images/team/2.jpg') }}" alt="Lipocumin" class="img-fluid">
                        <p>Kem hỗ trợ trị mụn, mờ thâm Lipocumin</p>
                        <p class="text-danger">318,000 đ</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('user/theme/images/team/3.jpg') }}" alt="Cetaphil" class="img-fluid">
                        <p>Sữa rửa mặt Cetaphil Gentle Skin Cleanser</p>
                        <p class="text-danger">360,000 đ</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('user/theme/images/team/4.jpg') }}" alt="Megaduo" class="img-fluid">
                        <p>Megaduo Gel hỗ trợ ngừa mụn trứng cá</p>
                        <p class="text-danger">139,000 đ</p>
                    </div>
                </div>
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
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }

        .compare-favorite {
            display: flex;
            gap: 15px;
            margin-bottom: 5px;
            color: #666;
            font-size: 13px;
        }

        .compare-favorite span {
            cursor: pointer;
        }
    </style>

    <script>
        function changeImage(imgElement) {
            document.getElementById("mainImage").src = imgElement.src;
        }

        function increaseQuantity() {
            let quantityInput = document.getElementById("quantity");
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decreaseQuantity() {
            let quantityInput = document.getElementById("quantity");
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }
    </script> --}}

    <!-- Slider Start -->
    <div class="container py-4">
        <div class="row">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-md-6 d-flex">
                <div class="d-flex flex-column me-3">
                    <img src="{{ asset('user/theme/images/team/2.jpg') }}" alt="Thumbnail 1"
                        class="img-thumbnail mb-2" onclick="changeImage(this)">
                    <img src="{{ asset('user/theme/images/team/3.jpg') }}" alt="Thumbnail 2"
                        class="img-thumbnail mb-2" onclick="changeImage(this)">
                    <img src="{{ asset('user/theme/images/team/4.jpg') }}" alt="Thumbnail 3"
                        class="img-thumbnail mb-2" onclick="changeImage(this)">
                </div>
                <div>
                    <img id="mainImage" src="{{ asset('user/theme/images/team/1.jpg') }}" alt="Sản phẩm chính"
                        class="img-fluid rounded shadow-lg">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <h2 class="fw-bold text-primary">Combo làm sạch, trị mụn, ngừa thâm sẹo Lipocumin</h2>
                <div class="rating text-warning">★★★★☆ (0 đánh giá)</div>
                <h4 class="mt-3">Bộ sản phẩm bao gồm:</h4>
                <ul>
                    <li>Sữa rửa mặt tẩy tế bào chết Clean 2in1 (Tuýp 100g)</li>
                    <li>Kem hỗ trợ trị mụn, mờ thâm Lipocumin (Tuýp 20g)</li>
                </ul>
                <h4>Công dụng:</h4>
                <p>Loại bỏ bã nhờn, dầu thừa, giúp da sáng mịn hơn.</p>
                <p class="text-danger fw-bold fs-3">520,000 đ <small
                        class="text-muted text-decoration-line-through">570,000 đ</small></p>
                <p class="text-success fw-bold">Còn hàng</p>
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-dark" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" value="1" class="form-control text-center mx-2"
                        style="width: 60px;">
                    <button class="btn btn-outline-dark" onclick="increaseQuantity()">+</button>
                </div>
                <div class="mt-3">
                    <button class="btn btn-dark btn-lg">Thêm vào giỏ hàng</button>
                    <button class="btn btn-danger btn-lg">Mua ngay</button>
                </div>

                <!-- Di chuyển phần So sánh & Yêu thích ra đây -->
                <div class="mt-3 compare-favorite">
                    <span>🔄 So sánh</span>
                    <span>❤️ Thêm vào yêu thích</span>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="product-info mt-3">
                    <p><strong>Mã sản phẩm:</strong> SF-2443-YF9Q</p>
                    <p><strong>Danh mục:</strong> Kem hỗ trợ trị mụn, Tẩy trang - Sữa rửa mặt</p>
                </div>
            </div>
        </div>

        <!-- Mô tả sản phẩm -->
        <div class="mt-4">
            <h4>Mô tả</h4>
            <p>Sữa rửa mặt tẩy tế bào chết Clean 2in1 kết hợp công thức Sâm Nghệ đỏ và các thành phần AHA, BHA, PHA...
            </p>
            <p>Kem hỗ trợ trị mụn, mờ thâm Lipocumin giúp giảm mụn, dưỡng ẩm da, làm đều màu da...</p>
        </div>

        <!-- Sản phẩm liên quan -->
        <div class="mt-4">
            <h4>Sản phẩm liên quan</h4>
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="{{ asset('user/theme/images/team/1.jpg') }}" alt="Clean 2in1" class="img-fluid">
                    <p>Sữa rửa mặt tẩy tế bào chết Clean 2in1</p>
                    <p class="text-danger">254,000 đ</p>
                </div>
                <div class="col-md-3 text-center">
                    <img src="{{ asset('user/theme/images/team/2.jpg') }}" alt="Lipocumin" class="img-fluid">
                    <p>Kem hỗ trợ trị mụn, mờ thâm Lipocumin</p>
                    <p class="text-danger">318,000 đ</p>
                </div>
                <div class="col-md-3 text-center">
                    <img src="{{ asset('user/theme/images/team/3.jpg') }}" alt="Cetaphil" class="img-fluid">
                    <p>Sữa rửa mặt Cetaphil Gentle Skin Cleanser</p>
                    <p class="text-danger">360,000 đ</p>
                </div>
                <div class="col-md-3 text-center">
                    <img src="{{ asset('user/theme/images/team/4.jpg') }}" alt="Megaduo" class="img-fluid">
                    <p>Megaduo Gel hỗ trợ ngừa mụn trứng cá</p>
                    <p class="text-danger">139,000 đ</p>
                </div>
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
    </style>

    <script>
        function changeImage(imgElement) {
            document.getElementById("mainImage").src = imgElement.src;
        }

        function increaseQuantity() {
            let quantityInput = document.getElementById("quantity");
            quantityInput.value = parseInt(quantityInput.value) + 1;
        }

        function decreaseQuantity() {
            let quantityInput = document.getElementById("quantity");
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        }
    </script>

    <!-- footer Start -->
    @include('user.partials.footer')

</body>

</html>
