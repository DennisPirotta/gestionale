<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobType extends Model
{
    use HasFactory;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'job_type_id');
    }

    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetails::class, 'job_type_id');
    }
}
