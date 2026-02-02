<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationAnnouncement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'author_id',
        'title',
        'content',
        'type',
        'priority',
        'is_active',
        'is_pinned',
        'published_at',
        'expires_at',
        'target_roles',
        'target_members',
        'view_count',
        'read_count',
        'attachment',
        'attachment_type'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'target_roles' => 'array',
        'target_members' => 'array',
        'view_count' => 'integer',
        'read_count' => 'integer'
    ];

    protected $attributes = [
        'type' => 'general',
        'priority' => 'normal',
        'is_active' => true,
        'is_pinned' => false,
        'view_count' => 0,
        'read_count' => 0
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                   ->where(function($q) {
                       $q->whereNull('expires_at')
                         ->orWhere('expires_at', '>', now());
                   });
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $user)
    {
        // Get user's membership in the organization
        $member = $query->getModel()->organization->members()
                        ->where(function($q) use ($user) {
                            $q->whereHas('student', fn($q) => $q->where('user_id', $user->id))
                              ->orWhereHas('teacher', fn($q) => $q->where('user_id', $user->id));
                        })
                        ->first();

        if (!$member) {
            return $query->where('id', '=', 0); // Safe way to return no results
        }

        return $query->where(function($q) use ($member) {
            $q->whereNull('target_roles')
              ->orWhereNull('target_members')
              ->orWhereIn('target_roles', [$member->role])
              ->orWhereIn('target_members', [$member->id]);
        });
    }

    // Accessors
    public function getFormattedTypeAttribute()
    {
        $types = [
            'general' => 'Umum',
            'urgent' => 'Penting',
            'meeting' => 'Rapat',
            'event' => 'Acara',
            'achievement' => 'Pencapaian',
            'reminder' => 'Pengingat'
        ];

        return $types[$this->type] ?? 'Umum';
    }

    public function getFormattedPriorityAttribute()
    {
        $priorities = [
            'low' => 'Rendah',
            'normal' => 'Normal',
            'high' => 'Tinggi',
            'urgent' => 'Darurat'
        ];

        return $priorities[$this->priority] ?? 'Normal';
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsPublishedAttribute()
    {
        return $this->published_at && $this->published_at->isPast();
    }

    public function getTimeAgoAttribute()
    {
        return $this->published_at->diffForHumans();
    }

    public function getExpiryTimeAttribute()
    {
        return $this->expires_at?->diffForHumans();
    }

    // Methods
    public function markAsRead($userId)
    {
        // This would typically be handled by a separate read tracking table
        // For now, we'll just increment the read count
        $this->increment('read_count');
    }

    public function incrementView()
    {
        $this->increment('view_count');
    }

    public function togglePin()
    {
        $this->update(['is_pinned' => !$this->is_pinned]);
    }

    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    public function extendExpiry($days)
    {
        $newExpiry = $this->expires_at ? $this->expires_at->addDays($days) : now()->addDays($days);
        $this->update(['expires_at' => $newExpiry]);
    }

    public function addTargetRole($role)
    {
        $roles = $this->target_roles ?? [];
        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->update(['target_roles' => $roles]);
        }
    }

    public function removeTargetRole($role)
    {
        $roles = $this->target_roles ?? [];
        if (($key = array_search($role, $roles)) !== false) {
            unset($roles[$key]);
            $this->update(['target_roles' => array_values($roles)]);
        }
    }

    public function addTargetMember($memberId)
    {
        $members = $this->target_members ?? [];
        if (!in_array($memberId, $members)) {
            $members[] = $memberId;
            $this->update(['target_members' => $members]);
        }
    }

    public function removeTargetMember($memberId)
    {
        $members = $this->target_members ?? [];
        if (($key = array_search($memberId, $members)) !== false) {
            unset($members[$key]);
            $this->update(['target_members' => array_values($members)]);
        }
    }

    public function canBeViewedBy($user)
    {
        if (!$this->is_active || !$this->is_published || $this->is_expired) {
            return false;
        }

        // Check if user is a member of the organization
        $member = $this->organization->members()
                        ->where(function($q) use ($user) {
                            $q->whereHas('student', fn($q) => $q->where('user_id', $user->id))
                              ->orWhereHas('teacher', fn($q) => $q->where('user_id', $user->id));
                        })
                        ->first();

        if (!$member) {
            return false;
        }

        // Check targeting
        if (empty($this->target_roles) && empty($this->target_members)) {
            return true; // No targeting, visible to all
        }

        $roleMatch = !empty($this->target_roles) && in_array($member->role, $this->target_roles);
        $memberMatch = !empty($this->target_members) && in_array($member->id, $this->target_members);

        return $roleMatch || $memberMatch;
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($announcement) {
            $announcement->sendNotifications();
        });

        static::updated(function ($announcement) {
            if ($announcement->isDirty(['is_active', 'is_pinned', 'priority'])) {
                \Illuminate\Support\Facades\Cache::forget("organization_{$announcement->organization_id}_announcements");
            }
        });
    }

    private function sendNotifications()
    {
        $organization = $this->organization;
        $members = $organization->activeMembers()->with(['student.user', 'teacher.user'])->get();

        foreach ($members as $member) {
            $user = $member->student?->user ?? $member->teacher?->user;
            
            if ($user && $user->id !== $this->author_id && $this->canBeViewedBy($user)) {
                OrganizationNotification::create([
                    'organization_id' => $this->organization_id,
                    'user_id' => $user->id,
                    'sender_id' => $this->author_id,
                    'title' => $this->priority === 'urgent' ? "🚨 PENTING: {$this->title}" : $this->title,
                    'message' => substr(strip_tags($this->content), 0, 100) . '...',
                    'type' => 'announcement',
                    'priority' => $this->priority,
                    'notifiable_type' => static::class,
                    'notifiable_id' => $this->id,
                    'action_url' => route('organizations.announcements.show', [$this->organization, $this])
                ]);
            }
        }
    }
}
