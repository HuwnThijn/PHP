document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const rememberCheckbox = document.getElementById('remember');

    // Kiểm tra nếu có thông tin đăng nhập được lưu
    if (localStorage.getItem('rememberedEmail')) {
        emailInput.value = localStorage.getItem('rememberedEmail');
        if (localStorage.getItem('rememberedPassword')) {
            passwordInput.value = localStorage.getItem('rememberedPassword');
            rememberCheckbox.checked = true;
        }
    }

    // Xử lý sự kiện khi form được submit
    loginForm.addEventListener('submit', function(event) {
        if (rememberCheckbox.checked) {
            // Lưu cả email và password vào localStorage
            localStorage.setItem('rememberedEmail', emailInput.value);
            localStorage.setItem('rememberedPassword', passwordInput.value);
        } else {
            // Xóa thông tin đăng nhập từ localStorage nếu không chọn remember me
            localStorage.removeItem('rememberedEmail');
            localStorage.removeItem('rememberedPassword');
        }
    });
}); 