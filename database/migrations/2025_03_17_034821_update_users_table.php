<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change(); // Thay đổi độ dài phone
            $table->enum('role', ['admin', 'user'])->default('user')->change(); // Cập nhật kiểu role
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->change(); // Hoàn tác độ dài phone
            $table->string('role')->default('user')->change(); // Hoàn tác kiểu role
        });
    }
};
