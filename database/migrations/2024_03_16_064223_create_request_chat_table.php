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
        Schema::create('request_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('RequestId');
            $table->unsignedBigInteger('RequestsentId');
            $table->unsignedBigInteger('ChatSenderId');
            $table->string('Message');
            $table->string('Attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_chat');
    }
};
