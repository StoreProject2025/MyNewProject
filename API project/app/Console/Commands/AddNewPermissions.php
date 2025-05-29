<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddNewPermissions extends Command
{
    protected $signature = 'permissions:add-new';
    protected $description = 'Add new permissions for categories and products management';

    public function handle()
    {
        $permissions = [
            'add_category' => 'Add Category',
            'edit_category' => 'Edit Category',
            'delete_category' => 'Delete Category',
            'view_categories' => 'View Categories',
            'add_product' => 'Add Product',
            'edit_product' => 'Edit Product',
            'delete_product' => 'Delete Product',
            'view_products' => 'View Products',
        ];

        $adminRole = Role::where('name', 'Admin')->first();

        foreach ($permissions as $name => $display_name) {
            $permission = Permission::firstOrCreate(['name' => $name], [
                'display_name' => $display_name
            ]);
            
            if ($adminRole) {
                $adminRole->givePermissionTo($permission);
            }
        }

        $this->info('New permissions have been added successfully!');
    }
} 