<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'created_by',
        'title',
        'description',
        'type',
        'status',
        'format',
        'filters',
        'parameters',
        'file_path',
        'file_name',
        'file_size',
        'generated_at',
        'expires_at',
        'download_count'
    ];

    protected $casts = [
        'filters' => 'array',
        'parameters' => 'array',
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
        'file_size' => 'integer',
        'download_count' => 'integer'
    ];

    protected $attributes = [
        'status' => 'pending',
        'format' => 'pdf',
        'download_count' => 0
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

    // Scopes
    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    // Accessors
    public function getFormattedTypeAttribute()
    {
        $types = [
            'membership' => 'Membership Report',
            'activity' => 'Activity Report',
            'engagement' => 'Engagement Report',
            'performance' => 'Performance Report',
            'financial' => 'Financial Report',
            'custom' => 'Custom Report'
        ];

        return $types[$this->type] ?? 'Custom Report';
    }

    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'generating' => 'Generating',
            'completed' => 'Completed',
            'failed' => 'Failed'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'generating' => 'info',
            'completed' => 'success',
            'failed' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'completed' && 
               !$this->is_expired && 
               Storage::exists($this->file_path);
    }

    public function getDownloadUrlAttribute()
    {
        if (!$this->is_available) return null;
        
        return route('reports.download', $this->id);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Methods
    public function generate()
    {
        $this->update(['status' => 'generating']);
        
        try {
            $generator = new ReportGenerator($this);
            $filePath = $generator->generate();
            
            $this->update([
                'status' => 'completed',
                'file_path' => $filePath,
                'file_name' => basename($filePath),
                'file_size' => Storage::size($filePath),
                'generated_at' => now()
            ]);
            
            return true;
        } catch (\Exception $e) {
            $this->update([
                'status' => 'failed'
            ]);
            
            \Log::error('Report generation failed: ' . $e->getMessage());
            return false;
        }
    }

    public function download()
    {
        if (!$this->is_available) {
            throw new \Exception('Report is not available for download');
        }
        
        $this->increment('download_count');
        
        return Storage::download($this->file_path, $this->file_name);
    }

    public function deleteFile()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    public function extendExpiry($days)
    {
        $newExpiry = $this->expires_at ? $this->expires_at->addDays($days) : now()->addDays($days);
        $this->update(['expires_at' => $newExpiry]);
    }

    public function canBeGenerated()
    {
        return in_array($this->status, ['pending', 'failed']);
    }

    public function canBeDownloaded()
    {
        return $this->is_available;
    }

    public function canBeDeleted()
    {
        return true; // All reports can be deleted by authorized users
    }

    // Static methods
    public static function createReport($data, $userId)
    {
        return static::create(array_merge($data, [
            'created_by' => $userId,
            'expires_at' => now()->addDays(30) // Default expiry
        ]));
    }

    public static function generateMembershipReport($organizationId, $filters = [], $userId)
    {
        $data = [
            'organization_id' => $organizationId,
            'title' => 'Membership Report',
            'description' => 'Comprehensive membership statistics and analytics',
            'type' => 'membership',
            'format' => 'pdf',
            'filters' => $filters
        ];
        
        return static::createReport($data, $userId);
    }

    public static function generateActivityReport($organizationId, $filters = [], $userId)
    {
        $data = [
            'organization_id' => $organizationId,
            'title' => 'Activity Report',
            'description' => 'Activity participation and performance metrics',
            'type' => 'activity',
            'format' => 'pdf',
            'filters' => $filters
        ];
        
        return static::createReport($data, $userId);
    }

    public static function generateEngagementReport($organizationId, $filters = [], $userId)
    {
        $data = [
            'organization_id' => $organizationId,
            'title' => 'Engagement Report',
            'description' => 'Member engagement and communication analytics',
            'type' => 'engagement',
            'format' => 'pdf',
            'filters' => $filters
        ];
        
        return static::createReport($data, $userId);
    }

    public static function generatePerformanceReport($organizationId, $filters = [], $userId)
    {
        $data = [
            'organization_id' => $organizationId,
            'title' => 'Performance Report',
            'description' => 'Overall organization performance metrics',
            'type' => 'performance',
            'format' => 'pdf',
            'filters' => $filters
        ];
        
        return static::createReport($data, $userId);
    }

    public static function getStatistics($organizationId = null)
    {
        $query = static::query();
        
        if ($organizationId) {
            $query->byOrganization($organizationId);
        }
        
        return [
            'total' => $query->count(),
            'completed' => $query->completed()->count(),
            'pending' => $query->pending()->count(),
            'failed' => $query->byStatus('failed')->count(),
            'recent' => $query->recent(7)->count(),
            'total_downloads' => $query->sum('download_count')
        ];
    }

    public static function cleanupExpired()
    {
        $expired = static::where('expires_at', '<', now())->get();
        
        foreach ($expired as $report) {
            $report->deleteFile();
            $report->delete();
        }
        
        return $expired->count();
    }

    // Boot events
    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($report) {
            $report->deleteFile();
        });

        static::created(function ($report) {
            // Auto-generate if it's a simple report
            if (in_array($report->type, ['membership', 'activity', 'engagement'])) {
                $report->generate();
            }
        });
    }
}
