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
        Schema::create('question_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id')->index();
            $table->string('table');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('order')->nullable();
            $table->timestamps();

            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_orders');
    }
};
