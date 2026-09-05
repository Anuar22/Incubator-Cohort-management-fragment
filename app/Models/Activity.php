<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Activity extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'completed_on' => 'date',
        'weekly_digest_sent_at' => 'datetime',
        'correction_deadline' => 'date',
        'corrected_on' => 'date',
    ];

    // Status Enums
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_RETURNED = 'returned_for_correction';
    public const STATUS_ESCALATED = 'open_escalated';

    // Core SOP Rule: Progress is 100% only when Verified, otherwise 0%
    protected function progress(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === self::STATUS_VERIFIED ? 100 : 0
        );
    }

    // Overdue detector for returned items
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_RETURNED 
            && $this->correction_deadline 
            && $this->correction_deadline->isPast() 
            && is_null($this->corrected_on);
    }

    // Direct access to stored file or external URL
    public function getEvidenceUrlAttribute(): ?string
    {
        return $this->evidence_file_path 
            ? Storage::disk('public')->url($this->evidence_file_path) 
            : $this->evidence_external_link;
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}