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
        Schema::create('blocked_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscribeId');
            $table->unsignedBigInteger('roleId');
            $table->unsignedBigInteger('createdBy');
            $table->timestamps();

              // Adding foreign key constraints
            $table->foreign('subscribeId')->references('id')->on('subscribers')->onDelete('cascade');
            $table->foreign('roleId')->references('id')->on('roles')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_list');
    }
};
