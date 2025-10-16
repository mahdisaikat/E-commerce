<?php


namespace App\Services;

use OwenIt\Auditing\Models\Audit;
use Throwable;

class AuditService
{
    public function audit($id, $event, $old_value, $new_value, $class)
    {
        try {
            Audit::create([
                'auditable_type' => $class,
                'auditable_id' => $id,
                'event' => $event,
                'old_values' => $old_value,
                'new_values' => $new_value,
                'url' => request()->url(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
            ]);

        } catch (Throwable $th) {

            return response()->json([
                'type' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

}
