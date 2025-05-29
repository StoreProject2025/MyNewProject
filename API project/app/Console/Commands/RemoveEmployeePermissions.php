<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemoveEmployeePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:remove-permissions {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unnecessary permissions from employee accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Starting permission cleanup...");
            
            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            // Get email from argument or ask for it
            $email = $this->argument('email');
            if (!$email) {
                $email = $this->ask('Enter employee email address:');
            }
            
            // Find the employee
            $employee = User::where('email', $email)->first();
            if (!$employee) {
                $this->error("Employee with email {$email} not found!");
                return 1;
            }
            
            // Check if user is an employee
            if (!$employee->hasRole('Employee')) {
                $this->error("User with email {$email} is not an Employee!");
                return 1;
            }
            
            // Display current permissions
            $this->info("Current permissions for {$employee->name} (ID: {$employee->id}):");
            $currentPermissions = $employee->getAllPermissions()->pluck('name')->toArray();
            $this->info(implode(', ', $currentPermissions));
            
            // Define the list of permissions to keep
            $keepPermissions = [
                'add_product', 'edit_product', 'delete_product', 'manage_products',
                'add_products', 'edit_products', 'delete_products', 'view_products',
                'add_customer_credit', 'show_users'
            ];
            
            // Hard-remove specific permissions from the database
            DB::beginTransaction();
            try {
                // Get user's current direct permissions
                $currentDirectPermissionIds = DB::table('model_has_permissions')
                    ->where('model_id', $employee->id)
                    ->where('model_type', get_class($employee))
                    ->pluck('permission_id')
                    ->toArray();
                
                $this->info("Found " . count($currentDirectPermissionIds) . " direct permissions");
                
                // Get permission IDs to keep
                $keepPermissionIds = Permission::whereIn('name', $keepPermissions)->pluck('id')->toArray();
                $this->info("Permission IDs to keep: " . implode(', ', $keepPermissionIds));
                
                // Calculate permission IDs to remove
                $removePermissionIds = array_diff($currentDirectPermissionIds, $keepPermissionIds);
                $this->info("Permission IDs to remove: " . implode(', ', $removePermissionIds));
                
                // Delete unnecessary permissions
                $deleted = DB::table('model_has_permissions')
                    ->where('model_id', $employee->id)
                    ->where('model_type', get_class($employee))
                    ->whereIn('permission_id', $removePermissionIds)
                    ->delete();
                
                $this->info("Deleted {$deleted} direct permission records");
                
                // Get the role permissions that we want to remove
                $employeeRole = Role::where('name', 'Employee')->first();
                if ($employeeRole) {
                    $rolePermissionsToRemove = [
                        'view_orders', 'update_order_status', 'toggle_favorite', 'edit_users'
                    ];
                    
                    // Get permission IDs from names
                    $rolePermissionIds = Permission::whereIn('name', $rolePermissionsToRemove)
                        ->pluck('id')
                        ->toArray();
                    
                    // Remove these from the role's permissions
                    if (!empty($rolePermissionIds)) {
                        $roleDeleted = DB::table('role_has_permissions')
                            ->where('role_id', $employeeRole->id)
                            ->whereIn('permission_id', $rolePermissionIds)
                            ->delete();
                        
                        $this->info("Deleted {$roleDeleted} role permission records");
                    }
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error removing permissions: " . $e->getMessage());
                $this->error("Error: " . $e->getMessage());
                return 1;
            }
            
            // Clear cache again
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            // Check final permissions
            $this->info("\nFinal permissions for {$employee->name}:");
            $finalPermissions = $employee->fresh()->getAllPermissions()->pluck('name')->toArray();
            $this->info(implode(', ', $finalPermissions));
            
            $this->info("\nPermissions cleanup completed for employee: {$employee->name}");
            return 0;
        } catch (\Exception $e) {
            Log::error("Unexpected error: " . $e->getMessage());
            $this->error("Unexpected error: " . $e->getMessage());
            return 1;
        }
    }
}
