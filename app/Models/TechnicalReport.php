<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TechnicalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'secondary_customer_id', 'order_id', 'number', 'user_id',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function secondary_customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'secondary_customer_id');
    }

    public function technical_report_details(): HasMany
    {
        return $this->hasMany(TechnicalReportDetails::class, 'technical_report_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
