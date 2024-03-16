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
        Schema::create('block_list', function (Blueprint $table) {
            $table->Id();
            $table->unsignedBigInteger('Subscribedid');
            $table->unsignedBigInteger('Contactid');
            $table ->smallInteger('RoleId');
            $table->string('Createdby');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_list');
    }
};
