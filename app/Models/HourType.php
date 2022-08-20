<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourType extends Model
{
    use HasFactory;

    public function hours(){
        return $this->hasMany(Hour::class,'hour_type_id');
    }
}
