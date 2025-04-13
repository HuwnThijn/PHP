<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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
        'provider_id',
        'age',
        'gender',
        'points',
        'total_spent',
        'last_transaction',
        'failed_appointments',
        'email_verified_at'
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
    
    /**
     * Tìm hoặc tạo user từ thông tin OAuth
     *
     * @param string $provider Tên nhà cung cấp (facebook, google, twitter)
     * @param \Laravel\Socialite\Contracts\User $providerUser
     * @return \App\Models\User
     */
    public static function createOrUpdateFromOAuth($provider, $providerUser)
    {
        // Tìm user dựa vào provider_id và provider
        $user = self::where('provider_id', $providerUser->getId())
                    ->where('provider', $provider)
                    ->first();
        
        // Nếu không tìm thấy, kiểm tra xem user có email này đã tồn tại chưa
        if (!$user) {
            $user = self::where('email', $providerUser->getEmail())->first();
            
            // Nếu user với email này đã tồn tại, cập nhật thông tin OAuth
            if ($user) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $providerUser->getId(),
                    'avatar' => $providerUser->getAvatar() ?: $user->avatar,
                ]);
            } else {
                // Xử lý dữ liệu từ Facebook
                $userData = [
                    'id_role' => 4, // Vai trò khách hàng
                    'id_rank' => 1, // Rank mặc định
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'phone' => '', // Giá trị mặc định cho phone
                    'avatar' => $providerUser->getAvatar(),
                    'provider' => $provider,
                    'provider_id' => $providerUser->getId(),
                    'status' => self::STATUS_ACTIVE,
                    'email_verified_at' => now(), // Đã xác minh email qua OAuth
                    'age' => null,
                    'gender' => null,
                    'address' => null,
                    'points' => 0,
                    'total_spent' => 0.00,
                    'last_transaction' => null,
                    'failed_appointments' => 0
                ];
                
                // Lấy thêm thông tin từ Facebook nếu có
                if ($provider === 'facebook') {
                    try {
                        $gender = null;
                        
                        // Xử lý giới tính từ Facebook nếu có
                        if (isset($providerUser->user['gender'])) {
                            $fbGender = $providerUser->user['gender'];
                            if ($fbGender === 'male') {
                                $gender = 'male';
                            } elseif ($fbGender === 'female') {
                                $gender = 'female';
                            } else {
                                $gender = 'other';
                            }
                            $userData['gender'] = $gender;
                        }
                        
                        // Xử lý thông tin tuổi từ Facebook nếu có
                        if (isset($providerUser->user['birthday'])) {
                            $birthday = \Carbon\Carbon::parse($providerUser->user['birthday']);
                            $age = $birthday->age;
                            $userData['age'] = $age;
                        }
                    } catch (\Exception $e) {
                        // Bỏ qua lỗi khi xử lý thông tin bổ sung
                        \Illuminate\Support\Facades\Log::warning('Không thể lấy thông tin bổ sung từ Facebook: ' . $e->getMessage());
                    }
                }
                
                // Tạo user mới
                $user = self::create($userData);
            }
        } else {
            // Cập nhật thông tin cho user hiện có
            $user->update([
                'name' => $providerUser->getName() ?: $user->name,
                'avatar' => $providerUser->getAvatar() ?: $user->avatar,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        }
        
        return $user;
    }
    
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
}
