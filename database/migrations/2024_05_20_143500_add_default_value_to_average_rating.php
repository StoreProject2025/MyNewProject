<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'average_rating')) {
                DB::statement('ALTER TABLE products MODIFY COLUMN average_rating DECIMAL(3,2) DEFAULT 0.00');
            } else {
                $table->decimal('average_rating', 3, 2)->default(0.00);
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'average_rating')) {
                $table->decimal('average_rating', 3, 2)->change();
            }
        });
    }
}; 