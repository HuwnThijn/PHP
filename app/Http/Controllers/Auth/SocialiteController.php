<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Chuyển hướng đến OAuth Provider
     *
     * @param  string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        // Kiểm tra provider có hợp lệ không (facebook, google, twitter)
        if (!in_array($provider, ['facebook', 'google', 'twitter'])) {
            return redirect()->route('user.login')->with('error', 'Phương thức đăng nhập không hợp lệ.');
        }
        
        try {
            // Nếu provider là Google, thêm tham số prompt=select_account bằng cách điều chỉnh URL
            if ($provider === 'google') {
                $googleDriver = Socialite::driver($provider);
                $redirectUrl = $googleDriver->redirect()->getTargetUrl();
                // Thêm tham số prompt=select_account vào URL
                $redirectUrl .= '&prompt=select_account';
                return redirect()->to($redirectUrl);
            }
            
            // Xử lý đặc biệt cho Facebook
            if ($provider === 'facebook') {
                // Cài đặt thêm quyền truy cập qua URL tham số
                return Socialite::driver($provider)->redirect();
            }
            
            // Xử lý đặc biệt cho Twitter
            if ($provider === 'twitter') {
                return Socialite::driver($provider)->redirect();
            }
            
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error('Lỗi chuyển hướng OAuth: ' . $e->getMessage());
            return redirect()->route('user.login')->with('error', 'Không thể kết nối đến dịch vụ đăng nhập. Vui lòng thử lại sau.');
        }
    }
    
    /**
     * Xử lý callback từ OAuth Provider
     *
     * @param  string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Debug thông tin request
            Log::info('OAuth Callback URL', [
                'url' => request()->fullUrl(),
                'code' => request()->input('code'),
                'state' => request()->input('state')
            ]);
            
            $providerUser = Socialite::driver($provider)->user();
            
            // Log thông tin người dùng từ provider (để debug)
            Log::info('OAuth user data', [
                'provider' => $provider,
                'id' => $providerUser->getId(),
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'avatar' => $providerUser->getAvatar(),
                'token' => $providerUser->token
            ]);
            
            // Kiểm tra email có null không
            if (empty($providerUser->getEmail())) {
                Log::warning('Email trống từ provider', ['provider' => $provider]);
                return redirect()->route('user.login')
                    ->with('error', 'Không thể lấy email từ tài khoản ' . ucfirst($provider) . '. Vui lòng sử dụng phương thức đăng nhập khác.');
            }
            
            try {
                // Tìm hoặc tạo người dùng
                $user = User::createOrUpdateFromOAuth($provider, $providerUser);
                
                // Kiểm tra user có được tạo thành công không
                if (!$user) {
                    Log::error('User không được tạo từ OAuth');
                    return redirect()->route('user.login')
                        ->with('error', 'Không thể tạo tài khoản. Vui lòng thử lại sau.');
                }
                
                Log::info('Đăng nhập OAuth thành công', [
                    'user_id' => $user->id_user,
                    'email' => $user->email
                ]);
                
                // Đăng nhập người dùng
                Auth::login($user);
                
                // Kiểm tra trạng thái tài khoản
                if (!$user->isActive()) {
                    Auth::logout();
                    if ($user->isTemporaryLocked()) {
                        return redirect()->route('user.login')
                            ->with('error', 'Tài khoản của bạn đang bị tạm khóa.');
                    } else if ($user->isPermanentLocked()) {
                        return redirect()->route('user.login')
                            ->with('error', 'Tài khoản của bạn đã bị khóa vĩnh viễn.');
                    }
                }
                
                return redirect()->intended('/')
                    ->with('success', 'Đăng nhập thành công qua ' . ucfirst($provider) . '!');
            } catch (\Exception $e) {
                Log::error('Lỗi xử lý OAuth user: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->route('user.login')
                    ->with('error', 'Lỗi xử lý thông tin đăng nhập. Vui lòng thử lại sau.');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi OAuth callback: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('user.login')
                ->with('error', 'Đăng nhập thất bại. Vui lòng thử lại sau.');
        }
    }
}
