<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_resumes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('resume_id');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('patronymic')->nullable();
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->integer('age')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('telegram')->nullable();

            $table->string('position')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->integer('expected_salary')->nullable();
            $table->string('expected_salary_currency')->nullable();

            $table->json('education')->nullable();

            $table->json('work_experience')->nullable();
            $table->integer('work_experience_in_months')->nullable();
            $table->boolean('is_work_experience_continuous')->nullable();

            $table->json('skills')->nullable();
            $table->json('languages')->nullable();
            $table->json('personal_qualities')->nullable();

            $table->timestamp('resume_updated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_resumes');
    }
};
