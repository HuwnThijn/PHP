<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Cosmetic;
use App\Models\Category;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\CartItem;
use App\Models\Review;

class UserController extends Controller
{
    public function index()
    {
        return view('user.theme.layout');
    }
    public function product($slug)
    {
        return view('user.theme.product');
    }
    public function detailsp($slug)
    {
        // Find the product by slug
        $product = Cosmetic::where('name', 'like', str_replace('-', '%', $slug))->first();
        
        if (!$product) {
            return abort(404);
        }
        
        // Get related products (assuming same category)
        $relatedProducts = Cosmetic::where('id_category', $product->id_category)
            ->where('id_cosmetic', '!=', $product->id_cosmetic)
            ->take(4)
            ->get();
            
        // Get reviews for this product
        $reviews = Review::where('id_cosmetic', $product->id_cosmetic)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.theme.detailsp', compact('product', 'relatedProducts', 'reviews'));
    }
    // public function doctor($slug)
    // {
    //     return view('user.theme.doctor');
    // }
    public function doctor()
    {
        // Get all doctors (users with role_id = 2)
        $doctors = User::where('id_role', 2)->get();
        
        // Get unique genders for filtering
        $genders = $doctors->pluck('gender')->unique()->filter()->values();
        
        // Age ranges for filtering
        $ageRanges = [
            '20-29' => [20, 29],
            '30-39' => [30, 39],
            '40-49' => [40, 49],
            '50+' => [50, 100]
        ];
        
        return view('user.theme.doctor', [
            'doctors' => $doctors,
            'genders' => $genders,
            'ageRanges' => $ageRanges
        ]);
    }
    public function about()
    {
        return view('user.theme.about');
    }
    public function service()
    {
        return view('user.theme.service');
    }
    public function contact()
    {
        return view('user.theme.contact');
    }
    public function department()
    {
        return view('user.theme.department');
    }
    public function departmentSingle()
    {
        return view('user.theme.department-single');
    }
    public function appoinment()
    {
        // Lấy danh sách bác sĩ (users với role_id = 2)
        $doctors = User::where('id_role', 2)->get();
        
        // Lấy danh sách dịch vụ
        $services = Service::where('is_active', true)->get();
        
        return view('user.theme.appoinment', [
            'doctors' => $doctors,
            'services' => $services
        ]);
    }
    public function confirmation()
    {
        return view('user.theme.confirmation');
    }
    
    /**
     * Lưu lịch hẹn mới
     */
    public function storeAppointment(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'id_service' => 'required|exists:services,id_service',
            'id_doctor' => 'required|exists:users,id_user',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'notes' => 'nullable|string',
        ]);
        
        // Tạo datetime từ date và time
        $appointmentDateTime = $request->appointment_date . ' ' . $request->appointment_time;
        
        // Tạo lịch hẹn mới
        $appointment = new Appointment();
        
        // Nếu người dùng đã đăng nhập, lưu ID người dùng
        if (Auth::check()) {
            $appointment->id_patient = Auth::id();
        } else {
            // Nếu là khách, lưu thông tin của khách
            $appointment->guest_name = $request->name;
            $appointment->guest_email = $request->email;
            $appointment->guest_phone = $request->phone;
        }
        
        $appointment->id_doctor = $request->id_doctor;
        $appointment->id_service = $request->id_service;
        $appointment->appointment_time = $appointmentDateTime;
        $appointment->status = 'scheduled';
        $appointment->notes = $request->notes;
        
        $appointment->save();
        
        // Lưu thông tin lịch hẹn vào session để hiển thị ở trang xác nhận
        session(['appointment' => $appointment->load('doctor', 'service')]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đặt lịch hẹn thành công!',
            'appointment_id' => $appointment->id_appointment
        ]);
    }
    
    /**
     * Hiển thị trang xác nhận đặt lịch
     */
    public function appointmentConfirmation()
    {
        $appointment = session('appointment');
        
        if (!$appointment) {
            return redirect()->route('user.appointment');
        }
        
        return view('user.theme.appointment-confirmation', [
            'appointment' => $appointment
        ]);
    }
    
    /**
     * Hiển thị trang lịch sử đặt lịch
     */
    public function appointmentHistory()
    {
        // Người dùng phải đăng nhập để xem lịch sử
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Vui lòng đăng nhập để xem lịch sử đặt lịch.');
        }
        
        // Lấy lịch sử đặt lịch của người dùng đã đăng nhập
        $appointments = Appointment::where('id_patient', Auth::id())
            ->orderBy('appointment_time', 'desc')
            ->with(['doctor', 'service'])
            ->paginate(10);
        
        return view('user.theme.appointment-history', [
            'appointments' => $appointments
        ]);
    }
    
    public function doctorSingle($id = null)
    {
        // Nếu không có ID được cung cấp, chuyển hướng về trang danh sách bác sĩ
        if (!$id) {
            return redirect()->route('doctors');
        }
        
        // Tìm bác sĩ theo ID
        $doctor = User::where('id_user', $id)
                      ->where('id_role', 2)
                      ->first();
        
        // Nếu không tìm thấy bác sĩ, chuyển hướng về trang danh sách bác sĩ
        if (!$doctor) {
            return redirect()->route('doctors')->with('error', 'Không tìm thấy thông tin bác sĩ.');
        }
        
        return view('user.theme.doctor-single', [
            'doctor' => $doctor
        ]);
    }
    
    public function store(Request $request)
    {
        // Handle AJAX suggestions request
        if ($request->ajax() && $request->has('query')) {
            $suggestions = Cosmetic::where('name', 'like', '%' . $request->query('query') . '%')
                ->where('isHidden', false)
                ->limit(5)
                ->get(['id_cosmetic', 'name', 'price']);
            return response()->json(['success' => true, 'suggestions' => $suggestions]);
        }

        // Get categories for sidebar
        $categories = Category::all();
        
        // Build product query
        $query = Cosmetic::where('isHidden', false);

        // Count active filters
        $activeFilters = 0;

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('id_category', $request->category);
            $activeFilters++;
        }

        // Apply price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
            $activeFilters++;
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
            if (!$request->filled('min_price')) $activeFilters++; // Only count as one filter if both min and max are set
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $activeFilters++;
        }

        // Apply sorting
        switch ($request->get('sort', 'default')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest('id_cosmetic');
                break;
        }

        // Get paginated products
        $products = $query->paginate(12)->withQueryString();

        // Clean up query parameters for response
        $queryParams = array_filter($request->only(['category', 'min_price', 'max_price', 'search', 'sort', 'view']), function($value) {
            return $value !== null && $value !== '';
        });

        // Get active filters for display
        $filters = [
            'active_count' => $activeFilters,
            'category_id' => $request->category,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'search' => $request->search,
            'sort' => $request->sort,
            'view' => $request->get('view', 'grid')
        ];

        // If AJAX request, return JSON response with rendered view
        if ($request->ajax()) {
            try {
                $view = view('user.theme.partials.product-list', [
                    'products' => $products,
                    'view' => $request->get('view', 'grid')
                ])->render();

                return response()->json([
                    'success' => true,
                    'html' => $view,
                    'filters' => $filters
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại.'
                ], 500);
            }
        }

        // For regular requests, return full view
        return view('user.theme.store', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $filters
        ]);
    }
    
    // Authentication Methods
    
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        
        return view('user.theme.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is active
            if ($user->status !== User::STATUS_ACTIVE) {
                Auth::logout();
                return redirect()->route('user.login')
                    ->with('error', 'Your account has been locked. Please contact support.');
            }
            
            return redirect()->intended(route('index'))
                ->with('success', 'You have been logged in successfully!');
        }
        
        return redirect()->route('user.login')
            ->with('password_error', 'The provided credentials do not match our records.')
            ->withInput($request->except('password'));
    }
    
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        
        return view('user.theme.register');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
            'terms' => 'required',
        ]);
        
        // Get default role (customer) and rank (bronze)
        $customerRole = Role::where('name', 'customer')->first();
        $defaultRank = Rank::where('name', 'bronze')->first();
        
        if (!$customerRole || !$defaultRank) {
            return redirect()->back()
                ->with('error', 'System configuration error. Please contact support.')
                ->withInput($request->except('password', 'password_confirmation'));
        }
        
        // Create verification token
        $verificationToken = \Illuminate\Support\Str::random(64);
        
        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $customerRole->id_role,
            'id_rank' => $defaultRank->id_rank,
            'status' => User::STATUS_ACTIVE,
            'email_verification_token' => $verificationToken,
            'phone' => '',
        ]);
        
        // Bỏ qua việc gửi email xác minh trong môi trường phát triển
        // $this->sendVerificationEmail($user, $verificationToken);
        
        // Đánh dấu email đã được xác minh
        $user->email_verified_at = now();
        $user->save();
        
        return redirect()->route('user.login')
            ->with('success', 'Registration successful! You can now log in with your credentials.');
    }
    
    private function sendVerificationEmail($user, $token)
    {
        $verificationUrl = route('user.verify.email', ['token' => $token]);
        
        Mail::send('emails.verify-email', ['user' => $user, 'verificationUrl' => $verificationUrl], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Verify Your Email Address');
        });
    }
    
    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('user.login')
                ->with('error', 'Invalid verification token.');
        }
        
        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();
        
        return redirect()->route('user.login')
            ->with('success', 'Your email has been verified successfully. You can now log in.');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('user.login')
            ->with('success', 'You have been logged out successfully.');
    }
    
    // Password Reset Methods
    
    public function showForgotPasswordForm()
    {
        return view('user.theme.forgot-password');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        // Tìm user với email đã cung cấp
        $user = User::where('email', $request->email)->first();
        
        // Nếu không tìm thấy user
        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }
        
        // Tạo token reset password
        $token = \Illuminate\Support\Str::random(64);
        
        // Lưu token vào database
        DB::table('password_resets')->where('email', $request->email)->delete();
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
        
        // Gửi email với link reset password
        $resetUrl = route('user.password.reset', ['token' => $token]);
        
        try {
            Mail::send('emails.reset-password', ['resetUrl' => $resetUrl, 'user' => $user], function($message) use ($user) {
                $message->to($user->email)->subject('Reset Your Password');
            });
            
            return back()->with('status', 'We have emailed your password reset link! Please check your email inbox.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Could not send reset link. Please check your email configuration or try again later. Error: ' . $e->getMessage()]);
        }
    }
    
    public function showResetPasswordForm($token)
    {
        // Tìm email từ token trong bảng password_resets
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();
        
        if (!$passwordReset) {
            return redirect()->route('user.password.request')
                ->withErrors(['email' => 'Invalid password reset token.']);
        }
        
        return view('user.theme.reset-password', [
            'token' => $token,
            'email' => $passwordReset->email
        ]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);
        
        // Kiểm tra token có hợp lệ không
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        
        if (!$passwordReset) {
            return back()->withErrors(['email' => 'This password reset token is invalid.']);
        }
        
        // Kiểm tra token có hết hạn không (mặc định 60 phút)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'This password reset token has expired.']);
        }
        
        // Cập nhật mật khẩu
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();
        
        // Xóa token đã sử dụng
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        return redirect()->route('user.login')
            ->with('status', 'Your password has been reset successfully!');
    }
    
    // Profile Management Methods
    
    public function showProfile()
    {
        return view('user.theme.profile');
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'phone' => 'required|string|max:20',
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'phone.required' => 'Số điện thoại không được để trống. Đây là thông tin bắt buộc.'
        ]);
        
        // Update user data
        $userData = [
            'phone' => $request->phone,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
        ];
        
        // Handle avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            try {
                // Create avatars directory if it doesn't exist
                $avatarsPath = public_path('storage/avatars');
                if (!file_exists($avatarsPath)) {
                    mkdir($avatarsPath, 0755, true);
                }
                
                // Delete old avatar if exists
                if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                    unlink(public_path('storage/' . $user->avatar));
                }
                
                // Generate a unique filename
                $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
                
                // Move the uploaded file directly to the public storage directory
                $request->file('avatar')->move($avatarsPath, $filename);
                
                // Save the relative path to the database
                $userData['avatar'] = 'avatars/' . $filename;
                
            } catch (\Exception $e) {
                return redirect()->route('user.profile')
                    ->with('error', 'Error uploading profile picture: ' . $e->getMessage());
            }
        }
        
        User::where('id_user', $user->id_user)->update($userData);
        
        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }
    
    public function showChangePasswordForm()
    {
        return view('user.theme.change-password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);
        
        $user = Auth::user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }
        
        // Update password
        User::where('id_user', $user->id_user)->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('user.profile.password')
            ->with('success', 'Password updated successfully!');
    }
    
    // Cart and Order Methods
    
    public function showCart()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // In a real implementation, you would fetch the cart items from the database
        // For now, we'll retrieve cart items from session
        $cartItems = session()->get('cart', []);
        
        return view('user.theme.cart', [
            'cartItems' => $cartItems
        ]);
    }
    
    public function addToCart(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check() && $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng'], 401);
        }
        
        $productId = $request->product_id;
        $product = Cosmetic::findOrFail($productId);
        
        $cart = session()->get('cart', []);
        
        // If item already exists in cart, increase quantity
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // Add item to cart with quantity 1
            $cart[$productId] = [
                'id' => $product->id_cosmetic,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1
            ];
        }
        
        session()->put('cart', $cart);
        
        if($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng 🥰',
                'cart_count' => count($cart)
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng 🥰');
    }
    
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity) {
            $cart = session()->get('cart', []);
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            // Calculate new totals
            $item_total = $cart[$request->id]['price'] * $cart[$request->id]['quantity'];
            $subtotal = $this->calculateSubtotal($cart);
            $shipping = 30000;
            $tax = 2000;
            $total = $subtotal + $shipping + $tax;
            
            if($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Giỏ hàng đã được cập nhật',
                    'item_total' => $item_total,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);
            }
            
            return redirect()->back()->with('success', 'Giỏ hàng đã được cập nhật');
        }
    }
    
    public function removeFromCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart', []);
            
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            
            // Calculate new totals
            $subtotal = $this->calculateSubtotal($cart);
            $shipping = count($cart) > 0 ? 30000 : 0;
            $tax = count($cart) > 0 ? 2000 : 0;
            $total = $subtotal + $shipping + $tax;
            
            if($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                    'cart_empty' => count($cart) == 0,
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'tax' => $tax,
                    'total' => $total
                ]);
            }
            
            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
        }
    }
    
    private function calculateSubtotal($cart)
    {
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }
    
    public function showOrders()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // In a real implementation, you would fetch the user's orders from the database
        // For now, we'll just return the view without order data
        // You can modify this to fetch orders from your database
        
        return view('user.theme.orders');
    }

    /**
     * Store a new product review
     */
    public function storeProductReview(Request $request)
    {
        // Validate the request
        $request->validate([
            'id_cosmetic' => 'required|exists:cosmetics,id_cosmetic',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để đánh giá sản phẩm'
            ], 401);
        }
        
        // Không kiểm tra đánh giá đã tồn tại nữa, luôn tạo đánh giá mới
        // Create new review
        $review = new Review();
        $review->id_cosmetic = $request->id_cosmetic;
        $review->id_user = Auth::id();
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        
        // Update product's average rating
        $this->updateProductRating($request->id_cosmetic);
        
        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã đánh giá sản phẩm',
            'review' => $review
        ]);
    }

    /**
     * Delete a product review
     */
    public function deleteProductReview(Request $request)
    {
        // Validate the request
        $request->validate([
            'id_review' => 'required|exists:reviews,id_review',
        ]);
        
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xóa đánh giá'
            ], 401);
        }
        
        // Find the review
        $review = Review::find($request->id_review);
        
        // Check if review exists
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đánh giá'
            ], 404);
        }
        
        // Check if user owns the review
        if ($review->id_user != Auth::id() && Auth::user()->id_role != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa đánh giá này'
            ], 403);
        }
        
        // Get the product ID before deleting the review
        $productId = $review->id_cosmetic;
        
        // Delete the review
        $review->delete();
        
        // Update product's average rating
        $this->updateProductRating($productId);
        
        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được xóa thành công'
        ]);
    }
    
    /**
     * Update product's average rating based on all reviews
     */
    private function updateProductRating($productId)
    {
        $product = Cosmetic::findOrFail($productId);
        $avgRating = Review::where('id_cosmetic', $productId)->avg('rating');
        
        // Round to 1 decimal place
        $product->rating = round($avgRating, 1);
        $product->save();
        
        return $product->rating;
    }
}
