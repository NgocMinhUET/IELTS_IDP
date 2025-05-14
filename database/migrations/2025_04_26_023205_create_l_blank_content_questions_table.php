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
        Schema::create('l_blank_content_questions', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->unsignedBigInteger('part_id');
            $table->text('title');
            $table->tinyInteger('answer_type')->comment('drag/drop or fill text into blank input');
            $table->string('answer_label')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('l_blank_content_questions');
    }
};
