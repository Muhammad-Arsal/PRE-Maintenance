<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class ClearAdminRolePermissions extends Command
{
    protected $signature = 'permissions:clear-admin-role';
    protected $description = 'Detach all permissions from the admin role (guard: admin)';

    public function handle()
    {
        $role = Role::findByName('admin', 'admin');
        $role->syncPermissions([]);
        app()['cache']->forget('spatie.permission.cache');
        $this->info("Cleared all permissions from 'admin' role.");
        return Command::SUCCESS;
    }
}


