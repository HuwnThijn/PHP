<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    
    /**
     * Khóa chính của bảng
     *
     * @var string
     */
    protected $primaryKey = 'id_service';
    
    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'image',
        'is_active'
    ];
    
    /**
     * Các thuộc tính cần ép kiểu
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Mối quan hệ với bảng appointments (nếu có)
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_service');
    }
}
