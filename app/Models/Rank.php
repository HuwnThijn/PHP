<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Rank extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_rank';
    protected $fillable = ['name', 'min_points'];
    
    public function users()
    {
        return $this->hasMany(User::class, 'id_rank');
    }
}
