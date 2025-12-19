<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'staff_user_id',
        'action',
        'description',
        'ip_address',
    ];

    public function staff()
    {
        return $this->belongsTo(StaffUser::class, 'staff_user_id');
    }
}
