<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Auditable
{
    protected array $audit_changes = [];

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }

    protected function auditActor(): array
    {
        $user = Auth::guard('admin')->user() ?: Auth::user();

        return [
            'id'   => $user?->getAuthIdentifier(),
            'name' => $user?->name ?? 'system',
        ];
    }

    protected function auditUrl(): ?string
    {
        $name = Str::plural(Str::kebab(class_basename($this))).'.edit';

        return app('router')->has($name)
            ? route($name, $this)
            : request()?->fullUrl();
    }

    public static function bootAuditable(): void
    {
        // Before update: stash changes
        static::updating(function ($model) {
            $changes = [];

            foreach ($model->getDirty() as $field => $new) {
                $old = $model->getOriginal($field);

                if ($old !== $new) {
                    $changes[$field] = ['from' => $old, 'to' => $new];
                }
            }

            $model->audit_changes = $changes;
        });

        // After update: log all changes in one entry
        static::updated(function ($model) {
            if (empty($model->audit_changes)) {
                return;
            }

            $actor = $model->auditActor();
            $url   = $model->auditUrl();
            $label = class_basename($model);

            // Build multi-line description
            $lines = [];
            foreach ($model->audit_changes as $field => $diff) {
                $from = $diff['from'] ?? '(empty)';
                $to   = $diff['to'] ?? '(empty)';
                $lines[] = "The {$field} of {$label} changed from \"{$from}\" to \"{$to}\"";
            }
            $desc = implode("\n", $lines);

            // Single log row
            $model->activityLogs()->create([
                'action'      => 'updated',
                'description' => $desc,
                'url'         => $url,
                'user_id'     => $actor['id'],
                'user_name'   => $actor['name'],
                'performed_at'=> now(),
                'changes'     => $model->audit_changes,
            ]);
        });

        // Created
        static::created(function ($model) {
            $actor = $model->auditActor();
            $label = class_basename($model);

            $model->activityLogs()->create([
                'action'      => 'created',
                'description' => "New {$label} created",
                'url'         => $model->auditUrl(),
                'user_id'     => $actor['id'],
                'user_name'   => $actor['name'],
                'performed_at'=> now(),
            ]);
        });

        // Deleted
        static::deleted(function ($model) {
            $actor = $model->auditActor();
            $label = class_basename($model);

            $model->activityLogs()->create([
                'action'      => 'deleted',
                'description' => "{$label} deleted",
                'url'         => null,
                'user_id'     => $actor['id'],
                'user_name'   => $actor['name'],
                'performed_at'=> now(),
                'changes'     => ['__deleted' => ['from' => false, 'to' => true]],
            ]);
        });

        // Restored (if using SoftDeletes)
        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                $actor = $model->auditActor();
                $label = class_basename($model);

                $model->activityLogs()->create([
                    'action'      => 'restored',
                    'description' => "{$label} restored",
                    'url'         => $model->auditUrl(),
                    'user_id'     => $actor['id'],
                    'user_name'   => $actor['name'],
                    'performed_at'=> now(),
                    'changes'     => ['__deleted' => ['from' => true, 'to' => false]],
                ]);
            });
        }
    }
}
