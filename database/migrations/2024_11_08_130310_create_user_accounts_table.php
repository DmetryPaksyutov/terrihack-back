<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->uuid('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['telegram']);
            $table->string('account_id');
            $table->string('profile_image')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
