<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
      'name'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,'customer_id');
    }

    public function technical_reports_first(): HasMany
    {
        return $this->hasMany(TechnicalReport::class,'customer_id');
    }
    public function technical_reports_second(): HasMany
    {
        return $this->hasMany(TechnicalReport::class,'secondary_customer_id');
    }
    public function expense_reports(): HasMany
    {
        return $this->hasMany(ExpenseReport::class,'customer_id');
    }
}
