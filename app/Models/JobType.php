<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobType extends Model
{
    use HasFactory;

    public const SVILUPPO_SOFTWARE = 1;

    public const MESSA_IN_SERVIZIO = 2;

    public const SAFETY = 3;

    public const COLLAUDO = 4;

    public const MODIFICHE = 5;

    public const RIUNIONI = 6;

    public const ASSISTENZA = 7;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'job_type_id');
    }

    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetails::class, 'job_type_id');
    }
}
