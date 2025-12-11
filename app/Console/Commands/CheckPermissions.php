<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CheckPermissions extends Command
{
    protected $signature = 'check:permissions';
    protected $description = 'Check user permissions';

    public function handle()
    {
        $this->info('=== USERS ===');
        $users = User::all();
        foreach ($users as $user) {
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->role}");
            $this->info("Permissions:");
            $this->table(['Permission', 'Value'], collect($user->getRolePermissions())->map(function($value, $key) {
                return [$key, $value ? 'true' : 'false'];
            })->toArray());
            $this->line('');
        }

        $this->info('=== ROLES ===');
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->info("Role: {$role->name} ({$role->slug})");
            $this->table(['Permission', 'Value'], collect($role->permissions)->map(function($value, $key) {
                return [$key, $value ? 'true' : 'false'];
            })->toArray());
            $this->line('');
        }
    }
}
