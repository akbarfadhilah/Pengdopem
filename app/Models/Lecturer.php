<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'expertise',
        'specialization',
        'bio',
        'quota',
        'used_quota',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(AdvisorSubmission::class);
    }

    public function approvedSubmissions()
    {
        return $this->hasMany(AdvisorSubmission::class)->where('status', 'approved');
    }

    // Helper methods
    public function getAvailableQuotaAttribute()
    {
        return max(0, $this->quota - $this->used_quota);
    }

    public function hasAvailableQuota()
    {
        return $this->available_quota > 0;
    }

    public function incrementUsedQuota()
    {
        $this->increment('used_quota');
    }

    public function decrementUsedQuota()
    {
        if ($this->used_quota > 0) {
            $this->decrement('used_quota');
        }
    }
}
