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
        Schema::create('wards', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->string('name')->comment('Tên phường/xã');
            $table->foreignId('district_id')->constrained('districts')->comment('Khóa ngoại đến quận/huyện');
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
