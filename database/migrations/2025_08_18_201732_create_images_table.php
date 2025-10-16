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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->enum('type', ['product', 'category', 'blog', 'user', 'customer', 'profile', 'thumbnail', 'creation', 'slider', 'other']);
            $table->unsignedBigInteger('imageable_id');
            $table->string('imageable_type');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('size')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
