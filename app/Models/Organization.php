<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\ResponseCache\Facades\ResponseCache;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'tagline', 'description', 'icon', 'color', 'image',
        'tags', 'programs', 'leadership', 'email', 'phone', 'location',
        'is_active', 'order'
    ];

    protected $casts = [
        'tags' => 'array',
        'programs' => 'array',
        'leadership' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    protected $attributes = [
        'color' => 'primary',
        'is_active' => true,
        'order' => 0
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

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            // Clear the response cache when an organization is updated
            ResponseCache::forget('/');
            ResponseCache::forget('/beranda');
            ResponseCache::forget('/organisasi');
        });
    }
}
