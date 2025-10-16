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
        Schema::create('incomes', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('income_source_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('session')->nullable();
            $table->date('date');
            $table->string('invoice_no')->nullable();
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
        Schema::dropIfExists('incomes');
    }
};
