<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_id',
        'sender_id',
        'title',
        'message',
        'type',
        'priority',
        'notifiable_type',
        'notifiable_id',
        'is_read',
        'read_at',
        'channel',
        'sent',
        'sent_at',
        'data',
        'action_url'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'data' => 'array'
    ];

    protected $attributes = [
        'type' => 'system',
        'priority' => 'normal',
        'is_read' => false,
        'sent' => false,
        'channel' => 'web'
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeSent($query)
    {
        return $query->where('sent', true);
    }

    public function scopePending($query)
    {
        return $query->where('sent', false);
    }

    // Accessors
    public function getFormattedTypeAttribute()
    {
        $types = [
            'announcement' => 'Pengumuman',
            'discussion' => 'Diskusi',
            'activity' => 'Kegiatan',
            'reminder' => 'Pengingat',
            'system' => 'Sistem'
        ];

        return $types[$this->type] ?? 'Sistem';
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

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsUrgentAttribute()
    {
        return $this->priority === 'urgent';
    }

    public function getIsHighPriorityAttribute()
    {
        return in_array($this->priority, ['high', 'urgent']);
    }

    // Methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            return true;
        }
        return false;
    }

    public function markAsUnread()
    {
        if ($this->is_read) {
            $this->update([
                'is_read' => false,
                'read_at' => null
            ]);
            return true;
        }
        return false;
    }

    public function markAsSent()
    {
        $this->update([
            'sent' => true,
            'sent_at' => now()
        ]);
    }

    public function send()
    {
        // This would integrate with actual notification channels
        // For now, we'll just mark as sent
        $this->markAsSent();
        
        // Could integrate with:
        // - Email (Mail facade)
        // - SMS (Twilio, etc.)
        // - Push notifications (Firebase, etc.)
        
        return true;
    }

    public function canBeSent()
    {
        return !$this->sent && $this->user && $this->user->isActive();
    }

    public function getActionUrlAttribute()
    {
        return $this->attributes['action_url'] ?? $this->getDefaultActionUrl();
    }

    private function getDefaultActionUrl()
    {
        switch ($this->type) {
            case 'announcement':
                return route('organizations.announcements.index', $this->organization);
            case 'discussion':
                return route('organizations.discussions.index', $this->organization);
            case 'activity':
                return route('organizations.activities.index', $this->organization);
            default:
                return route('organizations.show', $this->organization);
        }
    }

    // Static methods
    public static function createForOrganization($organization, $userId, $title, $message, $type = 'system', $priority = 'normal', $data = [])
    {
        return static::create([
            'organization_id' => $organization->id,
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'priority' => $priority,
            'data' => $data
        ]);
    }

    public static function createBulk($organization, $userIds, $title, $message, $type = 'system', $priority = 'normal', $data = [])
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notifications[] = [
                'organization_id' => $organization->id,
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'priority' => $priority,
                'data' => $data,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        return static::insert($notifications);
    }

    public static function getUnreadCount($userId, $organizationId = null)
    {
        $query = static::unread()->byUser($userId);
        
        if ($organizationId) {
            $query->byOrganization($organizationId);
        }
        
        return $query->count();
    }

    public static function markAllAsRead($userId, $organizationId = null)
    {
        $query = static::unread()->byUser($userId);
        
        if ($organizationId) {
            $query->byOrganization($organizationId);
        }
        
        return $query->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public static function cleanupOldNotifications($days = 90)
    {
        $cutoffDate = now()->subDays($days);
        
        return static::where('created_at', '<', $cutoffDate)
                    ->where('is_read', true)
                    ->delete();
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($notification) {
            // Auto-send for web channel
            if ($notification->channel === 'web') {
                $notification->send();
            }
        });

        static::updated(function ($notification) {
            // Clear user notification cache
            \Illuminate\Support\Facades\Cache::forget("user_{$notification->user_id}_notifications");
        });
    }
}
