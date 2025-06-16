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
        Schema::create('favorites', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người dùng yêu thích');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->comment('Bài đăng được yêu thích');
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
