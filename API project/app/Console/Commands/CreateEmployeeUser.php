<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateEmployeeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-employee {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new employee with product and customer management permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if email exists
        $email = $this->argument('email');
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Create employee
        $employee = new User();
        $employee->name = $this->argument('name');
        $employee->email = $email;
        $employee->password = bcrypt($this->argument('password'));
        $employee->email_verified_at = now(); // Auto verify
        $employee->save();

        // Assign employee role
        $employee->assignRole('Employee');

        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all permissions
        $allPermissions = Permission::pluck('name')->toArray();
        
        // Product permissions
        $productPermissions = [
            'add_product', 'add_products', 
            'edit_product', 'edit_products', 
            'delete_product', 'delete_products', 
            'view_products', 'manage_products'
        ];
        
        // User permissions
        $userPermissions = [
            'show_users', 'edit_users', 'add_customer_credit'
        ];
        
        // Combine all permissions to check
        $permissionsToAssign = array_merge($productPermissions, $userPermissions);
        
        // Assign permissions if they exist
        foreach ($permissionsToAssign as $permName) {
            if (in_array($permName, $allPermissions)) {
                $employee->givePermissionTo($permName);
                $this->info("Assigned permission: {$permName}");
            }
        }

        // Clear cache again
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->info("Employee created successfully with ID: {$employee->id}");
        $this->info("Name: {$employee->name}");
        $this->info("Email: {$employee->email}");
        
        return 0;
    }
}
