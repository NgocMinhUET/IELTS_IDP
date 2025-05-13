<?php

use App\Enum\Models\SkillSessionStatus;
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
        Schema::create('skill_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_session_id');
            $table->unsignedBigInteger('skill_id');
            $table->dateTime('expired_at');
            $table->dateTime('submit_expired_at');
            $table->tinyInteger('status')->default(SkillSessionStatus::IN_PROGRESS->value);
            $table->timestamps();

            $table->unique(['exam_session_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_sessions');
    }
};
