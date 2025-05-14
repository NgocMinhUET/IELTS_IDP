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
        Schema::create('blank_image_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->string('input_identify')->nullable()->comment('Distractor answers is null');
            $table->text('answer');
            $table->float('x')->unsigned()->nullable();
            $table->float('y')->unsigned()->nullable();
            $table->string('placeholder')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->timestamps();

            $table->foreign('question_id')->references('id')
                ->on('blank_image_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blank_image_answers');
    }
};
