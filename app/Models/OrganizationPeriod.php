<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'period_name',
        'start_date',
        'end_date',
        'is_active',
        'leadership_structure',
        'description',
        'member_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'leadership_structure' => 'array',
        'member_count' => 'integer'
    ];

    protected $attributes = [
        'is_active' => false,
        'member_count' => 0
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'organization_id', 'organization_id')
                    ->where('period', $this->period_name);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Accessors
    public function getDurationAttribute()
    {
        return $this->start_date->format('M Y') . ' - ' . $this->end_date->format('M Y');
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->is_active) {
            return 0;
        }
        
        return now()->diffInDays($this->end_date, false);
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date->isPast();
    }

    // Methods
    public function activate()
    {
        // Deactivate other periods for this organization
        $this->organization->periods()->where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activate this period
        $this->update(['is_active' => true]);
        
        return $this;
    }

    public function addLeadershipPosition($role, $memberId, $memberName)
    {
        $leadership = $this->leadership_structure ?? [];
        $leadership[$role] = [
            'member_id' => $memberId,
            'name' => $memberName,
            'appointed_at' => now()->toDateString()
        ];
        
        $this->update(['leadership_structure' => $leadership]);
    }

    public function removeLeadershipPosition($role)
    {
        $leadership = $this->leadership_structure ?? [];
        unset($leadership[$role]);
        
        $this->update(['leadership_structure' => $leadership]);
    }

    public function getLeadershipByRole($role)
    {
        $leadership = $this->leadership_structure ?? [];
        return $leadership[$role] ?? null;
    }

    public function updateMemberCount()
    {
        $this->update(['member_count' => $this->members()->count()]);
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($period) {
            // Ensure only one active period per organization
            if ($period->is_active) {
                $period->organization->periods()->update(['is_active' => false]);
            }
        });

        static::updating(function ($period) {
            // Ensure only one active period per organization
            if ($period->isDirty('is_active') && $period->is_active) {
                $period->organization->periods()->where('id', '!=', $period->id)->update(['is_active' => false]);
            }
        });
    }
}
