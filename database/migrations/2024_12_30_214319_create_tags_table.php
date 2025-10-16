<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedTinyInteger('type')
                ->default(0)
                ->comment('0=Unknown, 1=Color, 2=Size, 3=Material, 4=Brand, 5=Style');

            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->unique(['name', 'type']);
            $table->unique(['slug', 'type']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
