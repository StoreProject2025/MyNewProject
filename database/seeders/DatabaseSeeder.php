<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run RoleSeeder first
        $this->call(RoleSeeder::class);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        // Create test customer
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $customer->assignRole('Customer');

        // Create permissions
        $permissions = [
            'show_users' => 'Show Users',
            'edit_users' => 'Edit Users',
            'delete_users' => 'Delete Users',
            'admin_users' => 'Admin Users',
            'show_roles' => 'Show Roles',
            'edit_roles' => 'Edit Roles',
            'delete_roles' => 'Delete Roles',
            'admin_roles' => 'Admin Roles',
            'show_permissions' => 'Show Permissions',
            'edit_permissions' => 'Edit Permissions',
            'delete_permissions' => 'Delete Permissions',
            'admin_permissions' => 'Admin Permissions',
            'show_categories' => 'Show Categories',
            'edit_categories' => 'Edit Categories',
            'delete_categories' => 'Delete Categories',
            'admin_categories' => 'Admin Categories',
            'show_products' => 'Show Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'admin_products' => 'Admin Products',
            'show_orders' => 'Show Orders',
            'edit_orders' => 'Edit Orders',
            'delete_orders' => 'Delete Orders',
            'admin_orders' => 'Admin Orders',
            'manage_discounts' => 'Manage Product Discounts'
        ];

        // Run other seeders
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
