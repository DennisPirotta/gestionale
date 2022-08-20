<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    protected $fillable = [
      'hour_type_id', 'start', 'end', 'user_id'
    ];

    public function holidays(){
        return $this->hasMany(Holiday::class,'hour_id');
    }
    public function order_details(){
        return $this->hasMany(OrderDetails::class,'hour_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function hour_type(){
        return $this->belongsTo(HourType::class,'hour_type_id');
    }
}
