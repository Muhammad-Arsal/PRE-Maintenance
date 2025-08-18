<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // bootstrap the default admin role for the admin guard
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'admin',
        ]);
        app()['cache']->forget('spatie.permission.cache');
    }
}
