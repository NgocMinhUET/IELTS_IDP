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
        Schema::table('l_blank_content_questions', function (Blueprint $table) {
            $table->unsignedInteger('score')->nullable()->after('answer_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('l_blank_content_questions', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
