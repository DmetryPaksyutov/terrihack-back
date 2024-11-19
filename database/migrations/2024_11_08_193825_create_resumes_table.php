<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('pdf_path');
            $table->string('txt_path')->nullable();
            $table->enum('status', [
                'loaded',
                'error',
                'in_text',
                'in_database',
                'base_data_parsed',
                'AI_data_parsed',
                'parsed'
            ])->default('loaded');
            $table->string('status_text')->nullable();
            $table->string('hash');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
