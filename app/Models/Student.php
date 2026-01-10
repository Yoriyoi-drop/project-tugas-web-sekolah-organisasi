<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'nis',
        'email',
        'phone',
        'class',
        'address',
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_student')->withTimestamps();
    }

    /**
     * Generate a simple unique NIS for a newly created student.
     * Format: {year}{sequential number}, e.g. 2025001
     */
    /**
     * Generate a simple unique NIS for a newly created student.
     * Format: {year}{sequential number}, e.g. 2025001
     */
    public static function generateNis(): string
    {
        $yearPrefix = date('Y');

        // Get the highest NIS starting with the current year directly from the database
        $maxNis = static::where('nis', 'like', $yearPrefix . '%')->max('nis');

        if ($maxNis) {
            $next = intval($maxNis) + 1;
        } else {
            $next = intval($yearPrefix . '001');
        }

        // Ensure uniqueness (loop to handle potential race conditions)
        while (static::where('nis', (string) $next)->exists()) {
            $next++;
        }

        return (string) $next;
    }
}
