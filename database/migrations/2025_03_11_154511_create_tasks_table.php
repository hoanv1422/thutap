<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên công việc
            $table->text('description')->nullable(); // Mô tả công việc (có thể nullable)
            $table->date('deadline')->nullable(); // Ngày hết hạn (có thể nullable)
            $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled'])->default('pending'); // Trạng thái công việc

            // Người tạo (owner)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Người được phân công
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');

            // Mức độ ưu tiên: thấp, trung bình, cao, khẩn cấp
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            // Tiến độ hoàn thành (0 - 100%)
            $table->integer('progress')->default(0);

            // Tệp đính kèm (có thể nullable)
            $table->string('attachment')->nullable();

            // Ghi chú bổ sung
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
