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
        Schema::create('l_blank_content_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->string('input_identify')->nullable()->comment('Distractor answers is null');
            $table->text('answer');
            $table->string('placeholder')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->timestamps();

            $table->foreign('question_id')->references('id')
                ->on('l_blank_content_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('l_blank_content_answers');
    }
};
