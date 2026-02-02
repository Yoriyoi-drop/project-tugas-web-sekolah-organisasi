<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'student_id', 
        'teacher_id',
        'status',
        'role',
        'position',
        'period',
        'join_date',
        'end_date',
        'notes',
        'achievements',
        'skills'
    ];

    protected $casts = [
        'status' => 'string',
        'role' => 'string',
        'join_date' => 'date',
        'end_date' => 'date',
        'achievements' => 'array',
        'skills' => 'array',
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'status' => 'active',
        'role' => 'member',
        'join_date' => 'now'
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeLeadership($query)
    {
        return $query->whereIn('role', ['leader', 'vice_leader', 'secretary', 'treasurer']);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && (!$this->end_date || $this->end_date->isFuture());
    }

    public function getFullNameAttribute()
    {
        return $this->student?->name ?? $this->teacher?->name ?? 'Unknown';
    }

    public function getMemberTypeAttribute()
    {
        return $this->student ? 'student' : 'teacher';
    }

    public function getRoleDisplayNameAttribute()
    {
        $roleNames = [
            'member' => 'Anggota',
            'secretary' => 'Sekretaris',
            'treasurer' => 'Bendahara',
            'vice_leader' => 'Wakil Ketua',
            'leader' => 'Ketua'
        ];

        return $this->position ?? ($roleNames[$this->role] ?? 'Anggota');
    }

    // Methods
    public function promoteToRole($newRole, $position = null)
    {
        $this->update([
            'role' => $newRole,
            'position' => $position
        ]);
    }

    public function changeStatus($newStatus, $endDate = null)
    {
        $this->update([
            'status' => $newStatus,
            'end_date' => $endDate ?? ($newStatus === 'inactive' ? now() : null)
        ]);
    }

    public function addAchievement($achievement)
    {
        $achievements = $this->achievements ?? [];
        $achievements[] = [
            'title' => $achievement,
            'date' => now()->toDateString()
        ];
        
        $this->update(['achievements' => $achievements]);
    }

    public function addSkill($skill)
    {
        $skills = $this->skills ?? [];
        if (!in_array($skill, $skills)) {
            $skills[] = $skill;
            $this->update(['skills' => $skills]);
        }
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($member) {
            // Update organization member count
            $member->organization->increment('member_count');
        });

        static::deleted(function ($member) {
            // Update organization member count
            $member->organization->decrement('member_count');
        });
    }
}
