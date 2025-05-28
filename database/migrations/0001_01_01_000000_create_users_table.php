<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->string('email')->unique(); 
            $table->timestamp('email_verified_at')->nullable(); // وقت التحقق من الإيميل (اختياري)
            $table->string('password'); 
            $table->decimal('credit', 8, 2)->default(0.00);
            $table->boolean('is_admin')->default(false); // هل أدمن؟ (افتراضيًا لا)
            $table->rememberToken(); 
            $table->timestamps(); 
        });

        // جدول رموز إعادة تعيين كلمة السر
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // الإيميل كمفتاح أساسي
            $table->string('token'); // رمز إعادة التعيين
            $table->timestamp('created_at')->nullable(); // وقت الإنشاء
        });

       // جدول الجلسات (sessions)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // معرف الجلسة
            $table->foreignId('user_id')->nullable()->index(); // معرف المستخدم (اختياري)
            $table->string('ip_address', 45)->nullable(); // عنوان IP
            $table->text('user_agent')->nullable(); // معلومات المتصفح
            $table->longText('payload'); // بيانات الجلسة
            $table->integer('last_activity')->index(); // آخر نشاط
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};