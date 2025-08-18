<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncAdminPermissions extends Command
{
    protected $signature = 'permissions:sync-admin';
    protected $description = 'Create/update all admin permissions from config and grant them to the admin role';

    public function handle()
    {
        $permissions = config('admin_permissions');
        $created = 0;
        foreach (array_keys($permissions) as $perm) {
            $p = Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'admin',
            ]);
            if ($p->wasRecentlyCreated) {
                $created++;
            }
        }

        // Keep role minimal to allow per-user permissions; do not grant all to role
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);

        app()['cache']->forget('spatie.permission.cache');

        $this->info("Synced permissions. Created {$created} new permissions. Did not modify role permissions.");

        return Command::SUCCESS;
    }
}


