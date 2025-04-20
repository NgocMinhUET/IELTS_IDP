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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('team_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->text('explanation')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false);
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();

            $table->index('email');
            $table->index('team_id');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
