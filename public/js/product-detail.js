$(document).ready(function() {
    // Hàm đổi hình ảnh khi click vào thumbnail
    window.changeImage = function(imgSrc) {
        document.getElementById("mainImage").src = imgSrc;
    };

    // Hàm tăng số lượng
    window.increaseQuantity = function() {
        var quantityInput = document.getElementById("quantity");
        quantityInput.value = parseInt(quantityInput.value) + 1;
    };

    // Hàm giảm số lượng
    window.decreaseQuantity = function() {
        var quantityInput = document.getElementById("quantity");
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
        }
    };
    
    // Xử lý sự kiện click cho nút thêm vào giỏ hàng
    $("#addToCartBtn").on("click", function() {
        var btn = $(this);
        var productId = btn.data("product-id");
        var cartAddUrl = btn.data("cart-add-url");
        var cartUrl = btn.data("cart-url");
        var userLoginUrl = btn.data("login-url");
        var csrfToken = btn.data("csrf-token");
        var isLoggedIn = btn.data("is-logged-in");
        var quantity = document.getElementById("quantity").value;
        
        $.ajax({
            url: cartAddUrl,
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Đóng',
                        //showCancelButton: true,
                        //cancelButtonText: 'Đi đến giỏ hàng'
                    }).then(function(result) {
                        if (!result.isConfirmed) {
                            if (isLoggedIn === 1) {
                                window.location.href = cartUrl;
                            } else {
                                window.location.href = userLoginUrl;
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng',
                        icon: 'error',
                        confirmButtonText: 'Đóng'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    Swal.fire({
                        title: 'Bạn cần đăng nhập!',
                        text: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng',
                        icon: 'warning',
                        confirmButtonText: 'Đăng nhập ngay',
                        showCancelButton: true,
                        cancelButtonText: 'Để sau'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            window.location.href = userLoginUrl;
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi thêm vào giỏ hàng',
                        icon: 'error',
                        confirmButtonText: 'Đóng'
                    });
                }
            }
        });
    });
    
    // Xử lý sự kiện click cho nút mua ngay
    // $("#buyNowBtn").on("click", function() {
    //     var btn = $(this);
    //     var productId = btn.data("product-id");
    //     var cartAddUrl = btn.data("cart-add-url");
    //     var cartUrl = btn.data("cart-url");
    //     var userLoginUrl = btn.data("login-url");
    //     var csrfToken = btn.data("csrf-token");
    //     var isLoggedIn = btn.data("is-logged-in");
    //     var quantity = document.getElementById("quantity").value;
        
    //     $.ajax({
    //         url: cartAddUrl,
    //         type: 'POST',
    //         data: {
    //             product_id: productId,
    //             quantity: quantity,
    //             _token: csrfToken
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 if (isLoggedIn === 1) {
    //                     window.location.href = cartUrl;
    //                 } else {
    //                     window.location.href = userLoginUrl;
    //                 }
    //             } else {
    //                 Swal.fire({
    //                     title: 'Lỗi!',
    //                     text: response.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng',
    //                     icon: 'error',
    //                     confirmButtonText: 'Đóng'
    //                 });
    //             }
    //         },
    //         error: function(xhr) {
    //             if (xhr.status === 401) {
    //                 Swal.fire({
    //                     title: 'Bạn cần đăng nhập!',
    //                     text: 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng',
    //                     icon: 'warning',
    //                     confirmButtonText: 'Đăng nhập ngay',
    //                     showCancelButton: true,
    //                     cancelButtonText: 'Để sau'
    //                 }).then(function(result) {
    //                     if (result.isConfirmed) {
    //                         window.location.href = userLoginUrl;
    //                     }
    //                 });
    //             } else {
    //                 Swal.fire({
    //                     title: 'Lỗi!',
    //                     text: 'Có lỗi xảy ra khi thêm vào giỏ hàng',
    //                     icon: 'error',
    //                     confirmButtonText: 'Đóng'
    //                 });
    //             }
    //         }
    //     });
    // });
});
