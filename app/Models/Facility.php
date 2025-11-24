<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\ResponseCache\Facades\ResponseCache;

class Facility extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'icon',
        'image',
        'description',
        'category',
        'capacity',
        'location',
        'status',
        'features',
        'contact_person',
        'operating_hours',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'capacity' => 'integer',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            // Hapus cache respons ketika fasilitas diperbarui
            ResponseCache::forget('/');
            ResponseCache::forget('/beranda');
            ResponseCache::forget('/fasilitas');
        });
    }
}