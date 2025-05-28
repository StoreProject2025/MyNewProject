<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'add_products' => 'Add Products',
            'edit_products' => 'Edit Products',
            'delete_products' => 'Delete Products',
            'show_users' => 'Show Users',
            'edit_users' => 'Edit Users',
            'delete_users' => 'Delete Users',
            'admin_users' => 'Admin Users',
            'add_customer_credit' => 'Add Credit',
            'update_credit' => 'Update Credit'
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::where('name', $name)->update(['display_name' => $displayName]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration
    }
}; 