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
        Schema::create('post_feature', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->comment('Bài đăng');
            $table->foreignId('feature_id')->constrained('features')->onDelete('cascade')->comment('Đặc điểm nổi bật');
        });
    }

    public function down(): void {
        Schema::dropIfExists('post_feature');
    }
};
