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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Tác giả');
            $table->string('title')->comment('Tiêu đề bài viết');
            $table->text('content')->comment('Nội dung blog');
            $table->string('slug')->comment('Đường dẫn URL thân thiện');
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
