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
        Schema::create('choice_sub_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('choice_question_id');
            $table->text('question');
            $table->unsignedInteger('min_option')->default(1);
            $table->unsignedInteger('max_option')->nullable();
            $table->timestamps();

            $table->foreign('choice_question_id')->references('id')->on('choice_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choice_sub_questions');
    }
};
