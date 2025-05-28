<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            // User Management Permissions
            'show_users',
            'add_users',
            'edit_users',
            'delete_users',
            'admin_users',
            'assign_roles',
            
            // Credit Management
            'add_customer_credit',
            'update_credit',
            
            // Order Management
            'manage_orders',
            'view_orders',
            'update_order_status',
            
            // Product Management
            'manage_products',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_discounts'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $marketingManagerRole = Role::firstOrCreate(['name' => 'Marketing Manager', 'guard_name' => 'web']);
        $deliveryManagerRole = Role::firstOrCreate(['name' => 'Delivery Manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        // Assign all permissions to admin
        $adminRole->givePermissionTo($permissions);

        // Assign permissions to Marketing Manager
        $marketingManagerRole->givePermissionTo([
            'show_users',
            'add_users',
            'edit_users',
            'assign_roles',
            'manage_products',
            'add_products',
            'edit_products',
            'delete_products',
            'view_orders'
        ]);

        // Assign permissions to Delivery Manager
        $deliveryManagerRole->givePermissionTo([
            'show_users',
            'view_orders',
            'update_order_status',
            'manage_orders'
        ]);

        // Assign permissions to Employee
        $employeeRole->givePermissionTo([
            'show_users',
            'add_customer_credit',
            'update_credit',
            'view_orders',
            'update_order_status'
        ]);

        // Customer role has no special permissions by default
    }
} 