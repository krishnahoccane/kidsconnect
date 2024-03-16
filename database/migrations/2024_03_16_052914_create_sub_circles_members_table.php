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
        Schema::create('sub_circles_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Subscirclesid');
            $table->unsignedBigInteger('Subscribedid');
            $table->unsignedBigInteger('Contactid');
            $table->string('Createdby');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_circles_members');
    }
};
