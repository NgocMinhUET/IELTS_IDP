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
        Schema::table('choice_sub_questions', function (Blueprint $table) {
            $table->unsignedInteger('score')->nullable()->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('choice_sub_questions', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
