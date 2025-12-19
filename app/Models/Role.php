<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StaffUser;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // Relasi benar ke StaffUser
    public function staffUsers()
    {
        return $this->belongsToMany(StaffUser::class, 'staff_user_role', 'role_id', 'staff_user_id');
    }
}
