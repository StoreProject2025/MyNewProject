<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-permissions {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check permissions for a specific user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Find user by email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        // Display user information
        $this->info("User Information:");
        $this->info("ID: {$user->id}");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$user->email}");
        
        // Display roles
        $roles = $user->getRoleNames()->toArray();
        $this->info("Roles: " . implode(', ', $roles));
        
        // Display permissions
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $this->info("Permissions: " . implode(', ', $permissions));
        
        // Check specific product permissions
        $this->info("\nProduct Management Permissions:");
        $this->info("Can add products: " . ($user->hasPermissionTo('add_product') ? 'Yes' : 'No'));
        $this->info("Can edit products: " . ($user->hasPermissionTo('edit_product') ? 'Yes' : 'No'));
        $this->info("Can delete products: " . ($user->hasPermissionTo('delete_product') ? 'Yes' : 'No'));
        $this->info("Can manage products: " . ($user->hasPermissionTo('manage_products') ? 'Yes' : 'No'));
        
        // Check customer credit permissions
        $this->info("\nCustomer Management Permissions:");
        $this->info("Can add customer credit: " . ($user->hasPermissionTo('add_customer_credit') ? 'Yes' : 'No'));
        
        return 0;
    }
}
