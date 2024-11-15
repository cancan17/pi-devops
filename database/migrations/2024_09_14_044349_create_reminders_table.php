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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->constrained('users');
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('type');
            $table->string('color')->nullable();
            $table->datetime('alert')->nullable();
            $table->string('repeat')->nullable();
            $table->datetime('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
