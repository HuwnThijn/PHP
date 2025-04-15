<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cosmetic extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_cosmetic';
    protected $fillable = ['id_category', 'name', 'price', 'rating', 'isHidden', 'image'];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id_cosmetic');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_cosmetic');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'id_cosmetic');
    }
}
