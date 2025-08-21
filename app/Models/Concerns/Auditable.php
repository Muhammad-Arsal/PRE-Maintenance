<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait Auditable
{
    protected array $audit_changes = [];

    /**
     * Polymorphic relation: model has many logs.
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'model');
    }

    /**
     * Get the actor performing the action (admin guard preferred).
     */
    protected function auditActor(): array
    {
        $user = Auth::guard('admin')->user() ?: Auth::user();

        return [
            'id'   => $user?->getAuthIdentifier(),
            'name' => $user?->name ?? 'system',
        ];
    }

    /**
     * Build the proper URL depending on the action.
     */
    protected function auditUrl(string $action = null): ?string
    {
        $request = request();
        return $request->session()->previousUrl();
    }


    /**
     * Boot trait to hook into model lifecycle events.
     */
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

        // After update: log all changes in one row
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
                if($url){
                    $explored_array = explode('/', $url);
                    if(!in_array('edit', $explored_array) && !in_array('settings', $explored_array)) {
                        if(count($explored_array) > 5) {
                            $module_name = $explored_array[4];
                            $tab_name = $explored_array[6];

                            if(!empty($module_name) && !empty($tab_name)) {
                                $custom_message = "The {$field} in {$tab_name} tab of {$module_name} has been changed from \"{$from}\" to \"{$to}\"";
                            }    
                        }
                    }
                }
                $lines[] = $custom_message ?? "The {$field} of {$label} changed from \"{$from}\" to \"{$to}\"";
            }
            $desc = implode("\n", $lines);

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
            $url   = $model->auditUrl();

            $custom_message = "New {$label} created";

            if ($url) {
                $exploded = explode('/', trim($url, '/'));

                if (!in_array('edit', $exploded) && !in_array('settings', $exploded)) {
                    if (count($exploded) > 5) {
                        $module_name = $exploded[4] ?? null;
                        $tab_name    = $exploded[6] ?? null;

                        if (!empty($module_name) && !empty($tab_name)) {
                            $custom_message = "New {$label} has been created in {$tab_name} tab of {$module_name}";

                            if ($tab_name === 'correspondence') {

                                $new_url = $url;

                                if (end($exploded) === 'upload') {
                                    array_splice($exploded, -2);
                                }

                                elseif (end($exploded) === 'task') {
                                    array_pop($exploded);
                                }

                                $new_url = url(implode('/', $exploded));
                            }
                        }
                    }
                }
            }

            $model->activityLogs()->create([
                'action'       => 'created',
                'description'  => $custom_message,
                'url'          => $new_url ?? null,
                'user_id'      => $actor['id'],
                'user_name'    => $actor['name'],
                'performed_at' => now(),
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
