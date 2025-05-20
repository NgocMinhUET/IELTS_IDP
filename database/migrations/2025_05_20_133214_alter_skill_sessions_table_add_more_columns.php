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
        Schema::table('skill_sessions', function (Blueprint $table) {
            $table->integer('total_question')->nullable()->after('status');
            $table->integer('total_submitted_answer')->nullable()->after('total_question');
            $table->integer('total_correct_answer')->nullable()->after('total_submitted_answer');
            $table->integer('total_pending_answer')->nullable()->after('total_correct_answer');
            $table->float('total_score')->nullable()->after('total_pending_answer');
            $table->float('total_correct_score')->nullable()->after('total_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_sessions', function (Blueprint $table) {
            $table->dropColumn('total_question');
            $table->dropColumn('total_submitted_answer');
            $table->dropColumn('total_correct_answer');
            $table->dropColumn('total_score');
            $table->dropColumn('total_correct_score');
        });
    }
};
