<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'nip',
        'email',
        'phone',
        'subject',
        'qualification',
    ];

    /**
     * Generate a deterministic, unique NIP for a new teacher.
     * Format: {YYYY}{sequence}, e.g. 20250001
     * Ensures uniqueness by scanning existing NIPs for the year and incrementing.
     */
    public static function generateNip(): string
    {
        $year = date('Y');
        $prefix = $year;

        $max = self::where('nip', 'like', $prefix . '%')->max('nip');

        if (! $max) {
            return $prefix . str_pad(1, 4, '0', STR_PAD_LEFT);
        }

        $number = (int) substr($max, strlen($prefix));
        $next = $number + 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Attach model event to auto-generate NIP when missing.
     */
    protected static function booted()
    {
        static::creating(function (self $teacher) {
            if (empty($teacher->nip)) {
                $teacher->nip = self::generateNip();
            }
        });
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_teacher')->withPivot('role')->withTimestamps();
    }
}
