<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 'excerpt', 'content', 'icon', 'category', 'color',
        'author', 'is_featured', 'is_published', 'published_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    protected $attributes = [
        'color' => 'primary',
        'author' => 'Admin',
        'is_featured' => false,
        'is_published' => true
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: substr(strip_tags($this->content), 0, 150) . '...';
    }
}
