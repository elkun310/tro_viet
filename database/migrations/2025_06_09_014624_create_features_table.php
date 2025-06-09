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
        Schema::create('features', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->string('name')->comment("''
                Các giá trị có thể gồm:
                - Đầy đủ nội thất
                - Có gác
                - Có kệ bếp
                - Có máy lạnh
                - Có máy giặt
                - Có tủ lạnh
                - Có thang máy
                - Không chung chủ
                - Giờ giấc tự do
                - Có bảo vệ 24/24
                - Có hầm để xe
                - Vị trí & bản đồ
            ''");
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void {
        Schema::dropIfExists('features');
    }
};
