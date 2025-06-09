<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('post_images', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->comment('Bài đăng liên kết');
            $table->string('url')->comment('Đường dẫn ảnh');
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void {
        Schema::dropIfExists('post_images');
    }
};
