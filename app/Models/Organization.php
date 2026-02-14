<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\ResponseCache\Facades\ResponseCache;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'tagline', 'description', 'icon', 'color', 'image',
        'tags', 'programs', 'leadership', 'email', 'phone', 'location',
        'is_active', 'order', 'member_count'
    ];

    protected $casts = [
        'tags' => 'array',
        'programs' => 'array',
        'leadership' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
        'member_count' => 'integer'
    ];

    protected $attributes = [
        'color' => 'primary',
        'is_active' => true,
        'order' => 0,
        'member_count' => 0
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'organization_student')->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'organization_teacher')->withPivot('role')->withTimestamps();
    }

    // New membership relationships
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function activeMembers()
    {
        return $this->hasMany(Member::class)->where('status', 'active');
    }

    public function periods()
    {
        return $this->hasMany(OrganizationPeriod::class);
    }

    public function activePeriod()
    {
        return $this->hasOne(OrganizationPeriod::class)->where('is_active', true);
    }

    public function currentPeriod()
    {
        return $this->hasOne(OrganizationPeriod::class)
                   ->where('start_date', '<=', now())
                   ->where('end_date', '>=', now());
    }

    // Collaboration relationships
    public function discussions()
    {
        return $this->hasMany(OrganizationDiscussion::class);
    }

    public function activeDiscussions()
    {
        return $this->discussions()->active();
    }

    public function pinnedDiscussions()
    {
        return $this->discussions()->active()->pinned();
    }

    public function activities()
    {
        return $this->hasMany(OrganizationActivity::class);
    }

    public function upcomingActivities()
    {
        return $this->activities()->upcoming();
    }

    public function pastActivities()
    {
        return $this->activities()->past();
    }

    public function featuredActivities()
    {
        return $this->activities()->featured();
    }

    public function announcements()
    {
        return $this->hasMany(OrganizationAnnouncement::class);
    }

    public function activeAnnouncements()
    {
        return $this->announcements()->active()->published();
    }

    public function pinnedAnnouncements()
    {
        return $this->announcements()->active()->published()->pinned();
    }

    public function notifications()
    {
        return $this->hasMany(OrganizationNotification::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            // Hapus cache respons (Spatie)
            ResponseCache::forget('/');
            ResponseCache::forget('/beranda');
            ResponseCache::forget('/organisasi');
            
            // Hapus cache manual aplikasi
            \Illuminate\Support\Facades\Cache::forget('all_organizations');
        });

        static::creating(function ($org) {
            $org->slug = \Illuminate\Support\Str::slug($org->name . '-' . \Illuminate\Support\Str::random(5));
        });

        static::updating(function ($org) {
            if ($org->isDirty('name')) {
                $org->slug = \Illuminate\Support\Str::slug($org->name . '-' . \Illuminate\Support\Str::random(5));
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Membership management methods
    public function addMember($studentId, $role = 'member', $period = null, $position = null)
    {
        $period = $period ?? $this->getCurrentPeriodName();

        // Cek apakah siswa sudah menjadi anggota organisasi ini dalam periode yang sama
        $existingMember = $this->members()
            ->where('student_id', $studentId)
            ->where('period', $period)
            ->first();

        if ($existingMember) {
            throw new \Exception('Student is already a member of this organization');
        }

        return $this->members()->create([
            'student_id' => $studentId,
            'role' => $role,
            'position' => $position,
            'period' => $period,
            'join_date' => now()
        ]);
    }

    public function addTeacherMember($teacherId, $role = 'advisor', $period = null, $position = null)
    {
        $period = $period ?? $this->getCurrentPeriodName();
        
        return $this->members()->create([
            'teacher_id' => $teacherId,
            'role' => $role,
            'position' => $position,
            'period' => $period,
            'join_date' => now()
        ]);
    }

    public function removeMember($memberId)
    {
        return $this->members()->findOrFail($memberId)->delete();
    }

    public function getCurrentPeriodName()
    {
        $currentPeriod = $this->currentPeriod;
        if ($currentPeriod) {
            return $currentPeriod->period_name;
        }
        
        // Generate default period name if none exists
        return date('Y') . '/' . (date('Y') + 1);
    }

    public function getMemberCountByStatus()
    {
        $counts = $this->members()
                   ->selectRaw('status, COUNT(*) as count')
                   ->groupBy('status')
                   ->pluck('count', 'status')
                   ->toArray();

        $total = array_sum($counts);
        $counts['total'] = $total;

        return $counts;
    }

    public function getLeadershipMembers()
    {
        return $this->members()
                   ->leadership()
                   ->with(['student', 'teacher'])
                   ->get();
    }

    public function hasMember($studentId)
    {
        return $this->members()
                   ->where('student_id', $studentId)
                   ->where('status', 'active')
                   ->exists();
    }

    public function updateMemberCount()
    {
        $this->update(['member_count' => $this->activeMembers()->count()]);
    }

    // Collaboration methods
    public function createDiscussion($title, $content, $authorId, $type = 'discussion')
    {
        return $this->discussions()->create([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'type' => $type
        ]);
    }

    public function createActivity($data, $createdBy)
    {
        $data['organization_id'] = $this->id;
        $data['created_by'] = $createdBy;
        
        return $this->activities()->create($data);
    }

    public function createAnnouncement($title, $content, $authorId, $type = 'general', $priority = 'normal')
    {
        return $this->announcements()->create([
            'title' => $title,
            'content' => $content,
            'author_id' => $authorId,
            'type' => $type,
            'priority' => $priority,
            'published_at' => now()
        ]);
    }

    public function getRecentActivity()
    {
        // Optimized to prevent N+1 queries by getting all records at once
        $discussions = $this->discussions()
                           ->with('author:id,name')
                           ->latest()
                           ->limit(3)
                           ->select('id', 'organization_id', 'title', 'content', 'author_id', 'created_at', 'updated_at')
                           ->get();

        $activities = $this->activities()
                         ->latest()
                         ->limit(3)
                         ->select('id', 'organization_id', 'title', 'description', 'start_datetime', 'end_datetime', 'location', 'created_at', 'updated_at')
                         ->get();

        $announcements = $this->announcements()
                            ->latest()
                            ->limit(3)
                            ->select('id', 'organization_id', 'title', 'content', 'author_id', 'published_at', 'created_at', 'updated_at')
                            ->get();

        return collect()
            ->merge($discussions)
            ->merge($activities)
            ->merge($announcements)
            ->sortByDesc('created_at')
            ->take(5);
    }

    public function getUpcomingEvents($limit = 5)
    {
        return $this->upcomingActivities()
                   ->with(['organization:id,name,slug', 'registrations:id,activity_id']) // Optimized with specific columns
                   ->orderBy('start_datetime')
                   ->limit($limit)
                   ->select('id', 'organization_id', 'title', 'description', 'start_datetime', 'end_datetime', 'location', 'type', 'max_participants', 'created_at', 'updated_at') // Select specific columns to reduce data transfer
                   ->get();
    }

    public function getLatestDiscussions($limit = 5)
    {
        return $this->activeDiscussions()
                   ->with('author')
                   ->latest('last_reply_at')
                   ->limit($limit)
                   ->get();
    }

    public function getImportantAnnouncements($limit = 3)
    {
        return $this->activeAnnouncements()
                   ->whereIn('priority', ['high', 'urgent'])
                   ->latest('published_at')
                   ->limit($limit)
                   ->get();
    }

    public function getCollaborationStats()
    {
        return [
            'discussions' => $this->activeDiscussions()->count(),
            'activities' => $this->activities()->count(),
            'upcoming_activities' => $this->upcomingActivities()->count(),
            'announcements' => $this->activeAnnouncements()->count(),
            'total_notifications' => $this->notifications()->count()
        ];
    }
}
