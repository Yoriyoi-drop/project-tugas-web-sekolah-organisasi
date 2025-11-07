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
    public static function generateNis(): string
    {
        $yearPrefix = date('Y');

        // Get all numeric NIS values that start with the current year and take the max.
        $max = static::where('nis', 'like', $yearPrefix.'%')
            ->get()
            ->map(fn ($s) => intval($s->nis))
            ->filter()
            ->max();

        if ($max) {
            $next = $max + 1;
        } else {
            $next = intval($yearPrefix . '001');
        }

        // Ensure uniqueness (very small loop since collisions are unlikely)
        while (static::where('nis', (string) $next)->exists()) {
            $next++;
        }

        return (string) $next;
    }
}
