<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour_id', 'job_type_id', 'order_id', 'description', 'signed',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function hour(): BelongsTo
    {
        return $this->belongsTo(Hour::class, 'hour_id');
    }

    public function job_type(): BelongsTo
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }
}
