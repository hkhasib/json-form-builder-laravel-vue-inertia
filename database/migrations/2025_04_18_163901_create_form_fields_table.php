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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->onDelete('cascade');
            $table->string('label')->nullable();
            $table->string('name');
            $table->string('type');
            $table->json('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('required')->default(false);
            $table->json('validation_rules')->nullable();
            $table->integer('position')->default(0);

            $table->string('class_name')->nullable();
            $table->text('custom_style')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
