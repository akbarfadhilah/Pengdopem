<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'study_program',
        'semester',
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
    public function canSubmitMore()
    {
        return $this->submissions()->count() < 3;
    }

    public function hasApprovedAdvisor()
    {
        return $this->approvedSubmissions()->exists();
    }
}
