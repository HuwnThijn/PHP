<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_category';
    protected $fillable = ['name'];
    
    public function cosmetics()
    {
        return $this->hasMany(Cosmetic::class, 'id_category');
    }
}
