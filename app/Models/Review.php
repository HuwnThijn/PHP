<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_review';
    protected $fillable = ['id_cosmetic', 'id_user', 'comment', 'rating'];
    
    public function cosmetic()
    {
        return $this->belongsTo(Cosmetic::class, 'id_cosmetic');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
