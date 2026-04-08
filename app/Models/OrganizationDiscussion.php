<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \App\Models\Organization $organization
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationDiscussion active()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationDiscussion pinned()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationDiscussion byType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationDiscussion byOrganization(int $organizationId)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationDiscussion withReplies()
 */
class OrganizationDiscussion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'author_id',
        'parent_id',
        'title',
        'content',
        'type',
        'status',
        'is_pinned',
        'views',
        'reply_count',
        'like_count',
        'tags',
        'last_reply_at',
        'last_reply_by'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'views' => 'integer',
        'reply_count' => 'integer',
        'like_count' => 'integer',
        'tags' => 'array',
        'last_reply_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'active',
        'type' => 'discussion',
        'is_pinned' => false,
        'views' => 0,
        'reply_count' => 0,
        'like_count' => 0
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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('created_at');
    }

    public function lastReplyAuthor()
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeWithReplies($query)
    {
        return $query->withCount('replies');
    }

    // Accessors
    public function getIsMainDiscussionAttribute()
    {
        return is_null($this->parent_id);
    }

    public function getFormattedTypeAttribute()
    {
        $types = [
            'discussion' => 'Diskusi',
            'announcement' => 'Pengumuman',
            'question' => 'Pertanyaan',
            'poll' => 'Polling'
        ];

        return $types[$this->type];
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getLastReplyTimeAgoAttribute()
    {
        return $this->last_reply_at?->diffForHumans();
    }

    // Methods
    public function incrementView()
    {
        $this->increment('views');
    }

    public function addReply($content, $authorId)
    {
        $reply = $this->replies()->create([
            'organization_id' => $this->organization_id,
            'author_id' => $authorId,
            'content' => $content,
            'type' => 'discussion'
        ]);

        $this->update([
            'reply_count' => $this->replies()->count(),
            'last_reply_at' => now(),
            'last_reply_by' => $authorId
        ]);

        return $reply;
    }

    public function togglePin()
    {
        $this->update(['is_pinned' => !$this->is_pinned]);
    }

    public function lock()
    {
        $this->update(['status' => 'locked']);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    public function addTag($tag)
    {
        $tags = $this->tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    public function removeTag($tag)
    {
        $tags = $this->tags ?? [];
        if (($key = array_search($tag, $tags)) !== false) {
            unset($tags[$key]);
            $this->update(['tags' => array_values($tags)]);
        }
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::created(function ($discussion) {
            // Create notification for organization members
            if ($discussion->is_main_discussion) {
                $discussion->createNotifications();
            }
        });

        static::updated(function ($discussion) {
            // Clear cache
            \Illuminate\Support\Facades\Cache::forget("organization_{$discussion->organization_id}_discussions");
        });
    }

    private function createNotifications()
    {
        $organization = $this->organization;
        $members = $organization->activeMembers()->with(['student.user', 'teacher.user'])->get();

        foreach ($members as $member) {
            $user = $member->student->user ?? $member->teacher->user;

            if ($user && $user->id !== $this->author_id) {
                OrganizationNotification::create([
                    'organization_id' => $this->organization_id,
                    'user_id' => $user->id,
                    'sender_id' => $this->author_id,
                    'title' => "Diskusi Baru: {$this->title}",
                    'message' => substr(strip_tags($this->content), 0, 100) . '...',
                    'type' => 'discussion',
                    'notifiable_type' => static::class,
                    'notifiable_id' => $this->id,
                    'action_url' => route('organizations.discussions.show', [$this->organization, $this])
                ]);
            }
        }
    }
}
