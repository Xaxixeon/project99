<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StaffUser extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'staff_code',
        'name',
        'username',
        'email',
        'password',
        'is_active',
        'metadata',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata'  => 'array',
    ];

    /**
     * Roles relation
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'staff_user_role',
            'staff_user_id',
            'role_id'
        );
    }

    /**
     * Check single role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->exists();
    }

    /**
     * Check multiple roles (Policy helper)
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assign one role (without detach)
     */
    public function assignRole(string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }

    /**
     * Sync roles (replace existing)
     */
    public function syncRoles(array $roles): void
    {
        $roleIds = Role::whereIn('name', $roles)
            ->pluck('id')
            ->toArray();

        $this->roles()->sync($roleIds);
    }
}
        