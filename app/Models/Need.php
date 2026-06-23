<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Need extends Model
{
    use HasFactory;

    protected $fillable = [
        'refugee_id',
        'recorded_by',
        'category',
        'description',
        'urgency_level',
        'has_disability',
        'is_pregnant',
        'has_critical_health',
        'family_size',
        'priority_score',
        'status',
    ];

    protected $casts = [
        'has_disability'    => 'boolean',
        'is_pregnant'       => 'boolean',
        'has_critical_health' => 'boolean',
        'priority_score'    => 'decimal:2',
    ];

    const CATEGORIES = ['food', 'shelter', 'healthcare', 'education', 'protection'];
    const STATUSES   = ['pending', 'in_progress', 'resolved'];

    public function refugee()
    {
        return $this->belongsTo(Refugee::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
