<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Role extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_role';
    protected $fillable = ['name'];
    
    public function users()
    {
        return $this->hasMany(User::class, 'id_role');
    }
}
