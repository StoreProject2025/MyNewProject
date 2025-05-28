<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddCreditPermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'add_customer_credit']);
    }
} 