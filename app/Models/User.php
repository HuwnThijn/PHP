<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'id_role',
        'id_rank',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'specialization',
        'status',
        'email_verification_token',
        'avatar',
        'provider',
        'provider_id'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'string'
    ];

    // Các hằng số cho trạng thái
    const STATUS_ACTIVE = 'active';
    const STATUS_TEMPORARY_LOCKED = 'temporary_locked';
    const STATUS_PERMANENT_LOCKED = 'permanent_locked';
    
    public function isAdmin()
    {
        return $this->id_role === 1;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isTemporaryLocked()
    {
        return $this->status === self::STATUS_TEMPORARY_LOCKED;
    }

    public function isPermanentLocked()
    {
        return $this->status === self::STATUS_PERMANENT_LOCKED;
    }
    
    /**
     * Tạo hoặc cập nhật người dùng từ thông tin đăng nhập mạng xã hội
     *
     * @param string $provider Tên nhà cung cấp (facebook, google, twitter)
     * @param \Laravel\Socialite\Contracts\User $socialiteUser Thông tin người dùng từ mạng xã hội
     * @return \App\Models\User
     */
    public static function createOrUpdateFromOAuth($provider, SocialiteUser $socialiteUser)
    {
        // Tìm người dùng theo provider và provider_id
        $user = self::where('provider', $provider)
                    ->where('provider_id', $socialiteUser->getId())
                    ->first();
        
        // Nếu không tìm thấy, thử tìm theo email
        if (!$user && $socialiteUser->getEmail()) {
            $user = self::where('email', $socialiteUser->getEmail())->first();
        }
        
        $userData = [
            'name' => $socialiteUser->getName() ?? 'User',
            'email' => $socialiteUser->getEmail(),
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'avatar' => $socialiteUser->getAvatar(),
            'email_verified_at' => now(), // Đánh dấu email đã xác thực
        ];
        
        // Nếu đã tìm thấy user, cập nhật thông tin
        if ($user) {
            $user->update($userData);
            return $user;
        }
        
        // Nếu không tìm thấy, tạo mới
        return self::create(array_merge($userData, [
            'id_role' => 4, // Vai trò mặc định là người dùng thông thường
            'id_rank' => 1, // Cấp bậc mặc định
            'password' => bcrypt(Str::random(16)), // Tạo mật khẩu ngẫu nhiên
            'status' => self::STATUS_ACTIVE,
            'phone' => '', // Thêm giá trị mặc định cho phone nếu là bắt buộc
        ]));
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
    
    public function rank()
    {
        return $this->belongsTo(Rank::class, 'id_rank');
    }
    
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'id_patient');
    }
    
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'id_doctor');
    }
    
    public function patientMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_patient');
    }
    
    public function doctorMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'id_doctor');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_user');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_user');
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_user');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }
}
