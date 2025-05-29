<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateDeliveryRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-delivery-role {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Delivery Manager role and assign it to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Clear permission cache
            $this->info("Clearing permission cache...");
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            // Step 1: Create "Delivery Manager" role if it doesn't exist
            $this->info("Creating Delivery Manager role...");
            $deliveryRole = Role::firstOrCreate(['name' => 'Delivery Manager']);
            
            // Step 2: List all available permissions
            $this->info("Available permissions:");
            $allPermissions = Permission::all()->pluck('name')->toArray();
            $this->info(implode(', ', $allPermissions));
            
            // Step 3: Define necessary permissions for delivery role
            $permissionsToAssign = [
                'view_orders',
                'update_order_status',
            ];
            
            // Create any missing permissions
            foreach ($permissionsToAssign as $permName) {
                if (!in_array($permName, $allPermissions)) {
                    Permission::create(['name' => $permName]);
                    $this->info("Created new permission: {$permName}");
                }
            }
            
            // Step 4: Assign permissions to the role
            $this->info("Assigning permissions to Delivery Manager role...");
            
            foreach ($permissionsToAssign as $permName) {
                $permission = Permission::where('name', $permName)->first();
                if ($permission) {
                    $deliveryRole->givePermissionTo($permission);
                    $this->info("Assigned permission: {$permName}");
                }
            }
            
            // Step 5: If an email is provided, assign the role to that user
            if ($email = $this->argument('email')) {
                $user = User::where('email', $email)->first();
                
                if (!$user) {
                    $this->error("User with email {$email} not found!");
                } else {
                    // Remove other roles if needed (uncomment if necessary)
                    // $user->roles()->detach();
                    
                    // Assign the delivery role
                    $user->assignRole($deliveryRole);
                    $this->info("Assigned Delivery Manager role to user: {$user->name}");
                }
            } else {
                // Ask the user if they want to assign this role to a user
                if ($this->confirm('Would you like to assign this role to a user?')) {
                    $email = $this->ask('Enter user email:');
                    $user = User::where('email', $email)->first();
                    
                    if (!$user) {
                        $this->error("User with email {$email} not found!");
                    } else {
                        // Assign the delivery role
                        $user->assignRole($deliveryRole);
                        $this->info("Assigned Delivery Manager role to user: {$user->name}");
                    }
                }
            }
            
            // Clear cache again
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            
            $this->info("Delivery Manager role setup completed successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
