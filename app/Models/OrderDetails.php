<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function hour(){
        return $this->belongsTo(Hour::class,'hour_id');
    }
}
