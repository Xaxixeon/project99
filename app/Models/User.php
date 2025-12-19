<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'staff_code',
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * Relasi ke Role (many to many) kalau sudah ada tabel pivot staff_user_role
     */
    public function roles()
    {
        // SESUAIKAN nama pivot & kolom foreign key kalau berbeda
        return $this->belongsToMany(Role::class, 'staff_user_role', 'staff_user_id', 'role_id');
    }

    /**
     * Cek apakah user punya role tertentu (nama role)
     */
    public function hasRoleName(string $role): bool
    {
        // pakai relasi roles kalau ada
        if (method_exists($this, 'roles')) {
            return $this->roles()->where('name', $role)->exists();
        }

        // fallback ke kolom role (single)
        return $this->role === $role;
    }

    /**
     * Ambil daftar nama role user (array string)
     */
    public function roleNames(): array
    {
        if (method_exists($this, 'roles')) {
            return $this->roles()->pluck('name')->toArray();
        }

        return array_filter([$this->role ?? null]);
    }

    /**
     * Ambil nama role pertama (utama) user
     */
    public function firstRoleName(): ?string
    {
        $roles = $this->roleNames();
        return $roles[0] ?? null;
    }
}
