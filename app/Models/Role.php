<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the users that belong to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the abilities that belong to this role.
     */
    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class);
    }

    /**
     * Check if the role has the given ability.
     */
    public function hasAbility(string $ability): bool
    {
        return $this->abilities()->where('slug', $ability)->exists();
    }
}
