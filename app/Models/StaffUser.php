<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Role;

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

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata'  => 'array',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'staff_user_role', 'staff_user_id', 'role_id');
    }

    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }

    public function syncRoles($roles)
    {
        $roleIds = Role::whereIn('name', (array) $roles)->pluck('id')->toArray();
        $this->roles()->sync($roleIds);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }
}

