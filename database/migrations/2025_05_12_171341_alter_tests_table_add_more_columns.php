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
        Schema::table('tests', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->after('desc');
            $table->unsignedTinyInteger('approve_status')->default(\App\Enum\Models\ApproveStatus::PENDING->value)
                ->after('created_by');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approve_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('approve_status');
            $table->dropColumn('approved_by');
        });
    }
};
