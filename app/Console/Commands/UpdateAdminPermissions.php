<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;

class UpdateAdminPermissions extends Command
{
    protected $signature = 'update:admin-permissions';

    protected $description = 'Update admin role with kitchen and billing permissions';

    public function handle()
    {
        $adminRole = Role::where('slug', 'admin')->first();

        if (! $adminRole) {
            $this->error('Admin role not found!');

            return 1;
        }

        $this->info('Current permissions:');
        $this->table(['Permission', 'Value'], collect($adminRole->permissions)->map(function ($value, $key) {
            return [$key, $value ? 'true' : 'false'];
        })->toArray());

        // Add kitchen and billing permissions
        $permissions = $adminRole->permissions;
        $permissions['kitchen'] = true;
        $permissions['billing'] = true;

        $adminRole->permissions = $permissions;
        $adminRole->save();

        $this->info("\nUpdated permissions:");
        $this->table(['Permission', 'Value'], collect($adminRole->permissions)->map(function ($value, $key) {
            return [$key, $value ? 'true' : 'false'];
        })->toArray());

        $this->info('Admin permissions updated successfully!');

        return 0;
    }
}
