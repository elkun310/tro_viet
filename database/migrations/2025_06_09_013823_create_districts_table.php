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
        Schema::create('districts', function (Blueprint $table) {
            $table->id()->comment('Khóa chính');
            $table->string('name')->comment('Tên quận/huyện');
            $table->foreignId('province_id')->constrained('provinces')->comment('Khóa ngoại đến tỉnh/thành phố');
            $table->timestamps();
            $table->softDeletes()->comment('Thời điểm xoá mềm, NULL nếu chưa xoá');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
