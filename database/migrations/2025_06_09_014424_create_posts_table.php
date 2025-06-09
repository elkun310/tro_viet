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
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Người đăng bài');
            $table->string('code')->unique()->comment('Mã định danh bài viết hiển thị cho người dùng');
            $table->foreignId('category_id')->nullable()->constrained('categories')->comment('Danh mục cho thuê');
            $table->string('title')->comment('Tiêu đề bài đăng');
            $table->text('description')->comment('Nội dung chi tiết');
            $table->integer('price')->comment('Giá thuê theo nghìn VND');
            $table->float('area')->comment('Diện tích (m2)');
            $table->string('address')->comment('Địa chỉ chi tiết');
            $table->foreignId('ward_id')->nullable()->constrained('wards')->comment('Phường/Xã nơi cho thuê');
            $table->boolean('is_featured')->default(false)->comment('Đánh dấu nổi bật');
            $table->string('status')->comment('Trạng thái: active, pending, expired');
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void {
        Schema::dropIfExists('posts');
    }
};
