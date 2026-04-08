<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read \App\Models\Organization $organization
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity byOrganization(int $organizationId)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity past()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity ongoing()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity byType(string $type)
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity featured()
 * @method static \Illuminate\Database\Eloquent\Builder|OrganizationActivity registrationOpen()
 */
class OrganizationActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'created_by',
        'coordinator_id',
        'title',
        'description',
        'type',
        'status',
        'start_datetime',
        'end_datetime',
        'location',
        'is_online',
        'online_link',
        'max_participants',
        'registered_count',
        'registration_required',
        'registration_deadline',
        'requirements',
        'outcomes',
        'budget',
        'cover_image',
        'gallery_images',
        'view_count',
        'is_featured'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_online' => 'boolean',
        'max_participants' => 'integer',
        'registered_count' => 'integer',
        'registration_required' => 'boolean',
        'registration_deadline' => 'datetime',
        'requirements' => 'array',
        'outcomes' => 'array',
        'budget' => 'decimal:2',
        'gallery_images' => 'array',
        'view_count' => 'integer',
        'is_featured' => 'boolean'
    ];

    protected $attributes = [
        'type' => 'event',
        'status' => 'planning',
        'is_online' => false,
        'registered_count' => 0,
        'registration_required' => true,
        'view_count' => 0,
        'is_featured' => false
    ];

    // Relationships
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function registrations()
    {
        return $this->hasMany(ActivityRegistration::class, 'activity_id');
    }

    public function confirmedRegistrations()
    {
        return $this->registrations()->whereIn('status', ['confirmed', 'attended']);
    }

    public function attendedRegistrations()
    {
        return $this->registrations()->where('status', 'attended');
    }

    // Scopes
    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['planning', 'upcoming'])
                   ->where('start_datetime', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_datetime', '<', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
                   ->where('start_datetime', '<=', now())
                   ->where('end_datetime', '>=', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('registration_required', true)
                   ->where(function($q) {
                       $q->whereNull('registration_deadline')
                         ->orWhere('registration_deadline', '>', now());
                   });
    }

    // Accessors
    public function getFormattedTypeAttribute()
    {
        $types = [
            'meeting' => 'Rapat',
            'event' => 'Acara',
            'training' => 'Pelatihan',
            'competition' => 'Kompetisi',
            'social' => 'Sosial',
            'religious' => 'Keagamaan',
            'other' => 'Lainnya'
        ];

        return $types[$this->type];
    }

    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'planning' => 'Perencanaan',
            'upcoming' => 'Akan Datang',
            'ongoing' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $statuses[$this->status];
    }

    public function getDurationAttribute()
    {
        return $this->start_datetime->diffForHumans($this->end_datetime);
    }

    public function getIsRegistrationOpenAttribute()
    {
        if (!$this->registration_required) return false;
        if ($this->registration_deadline && $this->registration_deadline->isPast()) return false;
        if ($this->max_participants && $this->registered_count >= $this->max_participants) return false;
        
        return in_array($this->status, ['planning', 'upcoming']);
    }

    public function getAvailableSlotsAttribute()
    {
        if (!$this->max_participants) return null;
        return max(0, $this->max_participants - $this->registered_count);
    }

    public function getAttendanceRateAttribute()
    {
        if ($this->registered_count === 0) return 0;
        return round(($this->attendedRegistrations()->count() / $this->registered_count) * 100, 2);
    }

    // Methods
    public function registerMember($memberId, $registeredBy = null)
    {
        if (!$this->is_registration_open) {
            throw new \Exception('Registration is not open for this activity');
        }

        if ($this->max_participants && $this->registered_count >= $this->max_participants) {
            throw new \Exception('Activity is full');
        }

        $registration = $this->registrations()->create([
            'member_id' => $memberId,
            'registered_by' => $registeredBy ?? auth()->id(),
            'status' => 'registered'
        ]);

        $this->increment('registered_count');
        
        return $registration;
    }

    public function cancelRegistration($memberId)
    {
        $registration = $this->registrations()
                             ->where('member_id', $memberId)
                             ->whereIn('status', ['registered', 'confirmed'])
                             ->first();

        if ($registration) {
            $registration->update(['status' => 'cancelled']);
            $this->decrement('registered_count');
            return true;
        }

        return false;
    }

    public function checkInMember($memberId)
    {
        $registration = $this->registrations()
                             ->where('member_id', $memberId)
                             ->whereIn('status', ['registered', 'confirmed'])
                             ->first();

        if ($registration) {
            $registration->update([
                'status' => 'attended',
                'checked_in_at' => now()
            ]);
            return true;
        }

        return false;
    }

    public function updateStatus($newStatus)
    {
        $this->update(['status' => $newStatus]);
        
        // Send notifications for status changes
        if (in_array($newStatus, ['upcoming', 'cancelled'])) {
            $this->sendStatusNotifications($newStatus);
        }
    }

    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);
    }

    public function addGalleryImage($imagePath)
    {
        $gallery = $this->gallery_images ?? [];
        $gallery[] = $imagePath;
        $this->update(['gallery_images' => $gallery]);
    }

    public function removeGalleryImage($imagePath)
    {
        $gallery = $this->gallery_images ?? [];
        if (($key = array_search($imagePath, $gallery)) !== false) {
            unset($gallery[$key]);
            $this->update(['gallery_images' => array_values($gallery)]);
        }
    }

    private function sendStatusNotifications($status)
    {
        $members = $this->organization->activeMembers()->with(['student.user', 'teacher.user'])->get();

        foreach ($members as $member) {
            $user = $member->student->user ?? $member->teacher->user;

            if ($user) {
                $title = $status === 'upcoming' ? "Acara Akan Datang: {$this->title}" : "Acara Dibatalkan: {$this->title}";
                $message = $status === 'upcoming' 
                    ? "Acara {$this->title} akan dimulai pada {$this->start_datetime->format('d M Y H:i')}"
                    : "Acara {$this->title} telah dibatalkan.";

                OrganizationNotification::create([
                    'organization_id' => $this->organization_id,
                    'user_id' => $user->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'activity',
                    'priority' => $status === 'cancelled' ? 'high' : 'normal',
                    'notifiable_type' => static::class,
                    'notifiable_id' => $this->id,
                    'action_url' => route('organizations.activities.show', [$this->organization, $this])
                ]);
            }
        }
    }
}
