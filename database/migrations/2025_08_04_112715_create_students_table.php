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
        Schema::create('students', function (Blueprint $table)
        {
            $table->id();
            $table->string('student_id')->unique()->nullable();
            $table->string('name_bn')->nullable();
            $table->string('name_en')->nullable();
            $table->foreignId('section_id')->nullable()->constrained()->nullOnDelete();
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('present_address')->nullable();
            $table->date('dob')->nullable();
            $table->string('birth_certificate_no')->nullable();
            $table->string('religion')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Others'])->nullable();
            $table->string('class_applied')->nullable();
            $table->string('nationality')->nullable();
            $table->boolean('fee_waiver')->default(false);
            $table->tinyInteger('roll')->nullable();
            $table->string('shift')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('transport_type', ['Self', 'School Van'])->nullable();
            $table->decimal('monthly_fee', 10, 2)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
