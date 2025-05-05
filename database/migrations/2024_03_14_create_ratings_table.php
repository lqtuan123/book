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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('ratable');  // Tạo ratable_id và ratable_type
            $table->float('rating')->comment('Điểm đánh giá từ 1-5');
            $table->text('comment')->nullable()->comment('Bình luận đánh giá');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->comment('Trạng thái đánh giá');
            $table->timestamps();
            
            // Mỗi user chỉ được đánh giá một lần cho mỗi item
            $table->unique(['user_id', 'ratable_id', 'ratable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
}; 