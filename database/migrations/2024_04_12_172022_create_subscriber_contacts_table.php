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
        Schema::create('subscriber_contacts', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('subscriberId');
            $table->smallInteger('contactedId');
            $table->enum('status', [3, 4])->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_contacts');
    }
};
