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
        Schema::create('media_collections', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->string('collection')->nullable();
            $table->string('disk');
            $table->string('path');
            $table->string('visibility')->default('private');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_collections');
    }
};
