<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات
        $permissions = [
            'admin_users',
            'show_users',
            'edit_users',
            'delete_users',
            'add_users',
            'assign_roles',
            'manage_orders',
            'view_orders',
            'update_order_status',
            'manage_products',
            'add_products',
            'edit_products',
            'delete_products',
            'add_customer_credit',
            'update_credit'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // إنشاء الأدوار وصلاحياتها
        $roles = [
            'Admin' => $permissions, // Admin بياخد كل الصلاحيات
            'Employee' => [
                'show_users',
                'view_orders',
                'update_order_status',
                'add_customer_credit',
                'update_credit'
            ],
            'Marketing Manager' => [
                'show_users',
                'edit_users',
                'add_users',
                'manage_products',
                'add_products',
                'edit_products',
                'view_orders',
                'update_order_status'
            ],
            'Delivery Manager' => [
                'view_orders',
                'update_order_status',
                'manage_orders'
            ],
            'Customer' => [] // Customer مالهوش صلاحيات دلوقتي
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
