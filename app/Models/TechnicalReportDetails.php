<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalReportDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour_id', 'technical_report_id', 'nightEU', 'nightExtraEU',
    ];

    public function hour(): BelongsTo
    {
        return $this->belongsTo(Hour::class, 'hour_id');
    }

    public function technical_report(): BelongsTo
    {
        return $this->belongsTo(TechnicalReport::class, 'technical_report_id');
    }
}
