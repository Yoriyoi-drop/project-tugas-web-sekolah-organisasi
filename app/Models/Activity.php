<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\ResponseCache\Facades\ResponseCache;

class Activity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 'description', 'date', 'location'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now());
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            // Clear the response cache when an activity is updated
            ResponseCache::forget('/');
            ResponseCache::forget('/beranda');
            ResponseCache::forget('/kegiatan');
        });
    }
}
