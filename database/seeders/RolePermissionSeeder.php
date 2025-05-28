<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'add_products']);
        Permission::create(['name' => 'edit_products']);
        Permission::create(['name' => 'delete_products']);
        Permission::create(['name' => 'show_users']);
        Permission::create(['name' => 'edit_users']);
        Permission::create(['name' => 'delete_users']);
        Permission::create(['name' => 'admin_users']);
        Permission::create(['name' => 'add_customer_credit', 'display_name' => 'Add Credit']);
        Permission::create(['name' => 'update_credit', 'display_name' => 'Update Credit']);

        $adminRole = Role::create(['name' => 'Admin']);
        $employeeRole = Role::create(['name' => 'Employee']);
        $customerRole = Role::create(['name' => 'Customer']);

        $adminRole->givePermissionTo(['add_products', 'edit_products', 'delete_products', 'show_users', 'edit_users', 'delete_users', 'admin_users', 'add_customer_credit', 'update_credit']);
        $employeeRole->givePermissionTo(['show_users', 'add_customer_credit', 'update_credit']);
        $customerRole->givePermissionTo(['show_users']);

        $admin = \App\Models\User::where('email', 'test@example.com')->first();
        $admin->assignRole('Admin');
    }
}