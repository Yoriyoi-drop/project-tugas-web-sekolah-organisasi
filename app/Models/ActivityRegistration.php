<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'member_id',
        'registered_by',
        'status',
        'notes',
        'responses',
        'checked_in_at',
        'checked_out_at',
        'feedback',
        'rating'
    ];

    protected $casts = [
        'responses' => 'array',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'rating' => 'integer'
    ];

    protected $attributes = [
        'status' => 'registered'
    ];

    // Relationships
    public function activity()
    {
        return $this->belongsTo(OrganizationActivity::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    // Scopes
    public function scopeByActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAttended($query)
    {
        return $query->where('status', 'attended');
    }

    public function scopeConfirmed($query)
    {
        return $query->whereIn('status', ['confirmed', 'attended']);
    }

    public function scopeWithFeedback($query)
    {
        return $query->whereNotNull('feedback');
    }

    public function scopeRated($query)
    {
        return $query->whereNotNull('rating');
    }

    // Accessors
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'registered' => 'Terdaftar',
            'confirmed' => 'Dikonfirmasi',
            'attended' => 'Hadir',
            'absent' => 'Tidak Hadir',
            'cancelled' => 'Dibatalkan'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getMemberNameAttribute()
    {
        return $this->member?->full_name ?? 'Unknown';
    }

    public function getMemberTypeAttribute()
    {
        return $this->member?->member_type ?? 'unknown';
    }

    public function getHasCheckedInAttribute()
    {
        return !is_null($this->checked_in_at);
    }

    public function getHasCheckedOutAttribute()
    {
        return !is_null($this->checked_out_at);
    }

    public function getAttendanceDurationAttribute()
    {
        if ($this->checked_in_at && $this->checked_out_at) {
            return $this->checked_in_at->diffForHumans($this->checked_out_at, true);
        }
        
        return null;
    }

    public function getRatingStarsAttribute()
    {
        if (!$this->rating) return '';
        
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        
        return $stars;
    }

    // Methods
    public function confirm()
    {
        return $this->update(['status' => 'confirmed']);
    }

    public function cancel()
    {
        return $this->update(['status' => 'cancelled']);
    }

    public function checkIn()
    {
        return $this->update([
            'status' => 'attended',
            'checked_in_at' => now()
        ]);
    }

    public function checkOut()
    {
        return $this->update(['checked_out_at' => now()]);
    }

    public function markAbsent()
    {
        return $this->update(['status' => 'absent']);
    }

    public function submitFeedback($feedback, $rating = null)
    {
        $data = ['feedback' => $feedback];
        
        if ($rating !== null && $rating >= 1 && $rating <= 5) {
            $data['rating'] = $rating;
        }
        
        return $this->update($data);
    }

    public function updateResponse($key, $value)
    {
        $responses = $this->responses ?? [];
        $responses[$key] = $value;
        
        return $this->update(['responses' => $responses]);
    }

    public function getResponse($key, $default = null)
    {
        $responses = $this->responses ?? [];
        return $responses[$key] ?? $default;
    }

    public function canBeCheckedIn()
    {
        return in_array($this->status, ['registered', 'confirmed']) && is_null($this->checked_in_at);
    }

    public function canBeCheckedOut()
    {
        return !is_null($this->checked_in_at) && is_null($this->checked_out_at);
    }

    public function canSubmitFeedback()
    {
        return $this->status === 'attended' && is_null($this->feedback);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['registered', 'confirmed']) && 
               $this->activity?->start_datetime?->isFuture();
    }

    // Static methods
    public static function getStatistics($activityId)
    {
        $registrations = static::byActivity($activityId);
        
        return [
            'total' => $registrations->count(),
            'registered' => $registrations->byStatus('registered')->count(),
            'confirmed' => $registrations->byStatus('confirmed')->count(),
            'attended' => $registrations->attended()->count(),
            'absent' => $registrations->byStatus('absent')->count(),
            'cancelled' => $registrations->byStatus('cancelled')->count(),
            'checked_in' => $registrations->whereNotNull('checked_in_at')->count(),
            'with_feedback' => $registrations->withFeedback()->count(),
            'average_rating' => $registrations->rated()->avg('rating')
        ];
    }

    public static function getAttendanceRate($activityId)
    {
        $total = static::byActivity($activityId)->count();
        $attended = static::byActivity($activityId)->attended()->count();
        
        return $total > 0 ? round(($attended / $total) * 100, 2) : 0;
    }

    public static function bulkCheckIn($activityId, $memberIds)
    {
        $registrations = static::byActivity($activityId)
                              ->whereIn('member_id', $memberIds)
                              ->whereIn('status', ['registered', 'confirmed'])
                              ->get();

        $checkedIn = 0;
        
        foreach ($registrations as $registration) {
            if ($registration->checkIn()) {
                $checkedIn++;
            }
        }
        
        return $checkedIn;
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($registration) {
            // Update activity registered count
            $registration->activity->increment('registered_count');
        });

        static::updated(function ($registration) {
            // Handle status changes that affect counts
            if ($registration->isDirty('status')) {
                $activity = $registration->activity;
                
                // Recalculate registered count if needed
                if (in_array($registration->getOriginal('status'), ['registered', 'confirmed']) && 
                    !in_array($registration->status, ['registered', 'confirmed'])) {
                    $activity->decrement('registered_count');
                } elseif (!in_array($registration->getOriginal('status'), ['registered', 'confirmed']) && 
                         in_array($registration->status, ['registered', 'confirmed'])) {
                    $activity->increment('registered_count');
                }
            }
        });

        static::deleted(function ($registration) {
            // Update activity registered count
            if (in_array($registration->status, ['registered', 'confirmed'])) {
                $registration->activity->decrement('registered_count');
            }
        });
    }
}
