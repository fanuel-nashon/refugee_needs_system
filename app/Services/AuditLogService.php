<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function log(
        string $event,
        ?string $auditableType = null,
        ?int $auditableId = null,
        array $oldValues = [],
        array $newValues = [],
        ?int $refugeeActorId = null
    ): void {
        AuditLog::create([
            'event'            => $event,
            'auditable_type'   => $auditableType,
            'auditable_id'     => $auditableId,
            'performed_by'     => Auth::id(),
            'refugee_actor_id' => $refugeeActorId,
            'old_values'       => $oldValues ?: null,
            'new_values'       => $newValues ?: null,
            'ip_address'       => request()->ip(),
        ]);
    }
}
