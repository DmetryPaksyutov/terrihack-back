<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->unique('hash');
        });
    }

    public function down(): void
    {
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropUnique('resumes_hash_unique');
            $table->dropColumn('name');
        });
    }
};
