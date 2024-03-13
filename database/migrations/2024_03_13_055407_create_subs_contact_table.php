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
        Schema::create('subs_contact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscribeId');
            $table->timestamps();

             // Adding foreign key constraints
        $table->foreign('subscribeId')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subs_contact');
    }
};
