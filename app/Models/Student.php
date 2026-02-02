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

    // New membership relationships
    public function memberships()
    {
        return $this->hasMany(Member::class);
    }

    public function activeMemberships()
    {
        return $this->memberships()->where('status', 'active');
    }

    public function leadershipRoles()
    {
        return $this->memberships()->leadership();
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

    // Membership methods
    public function joinOrganization($organizationId, $role = 'member')
    {
        $organization = Organization::findOrFail($organizationId);
        
        // Check if already a member
        if ($this->hasMembership($organizationId)) {
            throw new \Exception("Student is already a member of this organization");
        }
        
        return $organization->addMember($this->id, $role);
    }

    public function leaveOrganization($organizationId)
    {
        $membership = $this->memberships()
                          ->where('organization_id', $organizationId)
                          ->where('status', 'active')
                          ->first();
                          
        if ($membership) {
            $membership->changeStatus('inactive');
            return true;
        }
        
        return false;
    }

    public function hasMembership($organizationId)
    {
        return $this->memberships()
                   ->where('organization_id', $organizationId)
                   ->where('status', 'active')
                   ->exists();
    }

    public function getOrganizationRoles($organizationId)
    {
        return $this->memberships()
                   ->where('organization_id', $organizationId)
                   ->where('status', 'active')
                   ->pluck('role')
                   ->toArray();
    }

    public function isLeaderInOrganization($organizationId)
    {
        return $this->memberships()
                   ->where('organization_id', $organizationId)
                   ->where('status', 'active')
                   ->leadership()
                   ->exists();
    }

    public function getActiveOrganizations()
    {
        return $this->activeMemberships()
                   ->with('organization')
                   ->get()
                   ->pluck('organization');
    }

    public function getTotalOrganizations()
    {
        return $this->activeMemberships()->count();
    }
}
