<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
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