<?php

use App\Enum\Models\AnswerResult;
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
        Schema::create('skill_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skill_session_id');
            $table->string('question_model');
            $table->string('question_id'); //TODO: refactor
            $table->tinyInteger('question_type');
            $table->longText('answer');
            $table->tinyInteger('answer_result')->default(AnswerResult::PENDING);
            $table->timestamps();

            $table->unique(['skill_session_id', 'question_model', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_answers');
    }
};
