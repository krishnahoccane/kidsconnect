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
        Schema::create('request_sent_to', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('RequestId');
            $table->unsignedBigInteger('RequestFromId');
            $table->unsignedBigInteger('RequestToId');
            $table->unsignedBigInteger('Receiverstatus');
            $table->timestamp('ReceiverStatusDate')->nullable();
            $table->string('ReceiverFeedBack');
            $table->timestamp('RecFeedbackDate')->nullable();
            $table->string('SenderFeedBack');
            $table->string('SenderFeedBackDate');
            $table->unsignedBigInteger('StatusId');
            $table->timestamp('UpdatedBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_sent_to');
    }
};
