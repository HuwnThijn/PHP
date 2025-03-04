<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_inventory';
    protected $fillable = ['id_cosmetic', 'quantity', 'supplier', 'last_updated'];
    
    public function cosmetic()
    {
        return $this->belongsTo(Cosmetic::class, 'id_cosmetic');
    }
}
