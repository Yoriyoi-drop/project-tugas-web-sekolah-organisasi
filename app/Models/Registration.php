<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id', 'name', 'email', 'phone', 'class', 'nis',
        'address', 'motivation', 'skills', 'experiences', 'status'
    ];

    protected $casts = [
        'skills' => 'array',
        'experiences' => 'array'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}