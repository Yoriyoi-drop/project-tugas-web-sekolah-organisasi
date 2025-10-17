<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'type', 'tagline', 'description', 'icon', 'color',
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
}
