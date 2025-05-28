<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'user:assign-admin {email}';
    protected $description = 'Assign Admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            // Clear cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email {$email} not found!");
                return 1;
            }
            
            // Get or create Admin role
            $adminRole = Role::firstOrCreate(['name' => 'Admin']);
            
            // Remove existing roles
            $user->roles()->detach();
            
            // Assign Admin role
            $user->assignRole($adminRole);
            
            // Update is_admin flag
            $user->is_admin = true;
            $user->save();
            
            // Clear cache again
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            $this->info("Admin role assigned successfully to {$user->name}!");
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
} 