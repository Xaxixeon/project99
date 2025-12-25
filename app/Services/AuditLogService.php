<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    public static function log(
        string $action,
        string $subjectType,
        ?int $subjectId = null,
        array $old = [],
        array $new = []
    ): void {
        $user = auth('staff')->user();

        AuditLog::create([
            'user_id'      => $user?->id,
            'user_role'    => $user?->role,
            'action'       => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'old_values'   => $old ?: null,
            'new_values'   => $new ?: null,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);
    }
}
