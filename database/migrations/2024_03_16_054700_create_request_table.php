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
        Schema::create('request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('SubscriberId');
            $table->unsignedBigInteger('SubschildId');
            $table->string('Subject');
            $table->string('RequestFor');
            $table->timestamp('EventFrom')->nullable();
            $table->timestamp('EventTo')->nullable();
            $table->string('Keywords');
            $table->string('RecordType');
            $table->unsignedBigInteger('Statusid');
            $table->string('LocationType');
            $table->string('Location');
            $table->string('PickDropInfo');
            $table->string('SpecialNotes');
            $table->unsignedBigInteger('PrimaryResponsibleId');
            $table->string('ActivityType');
            $table->string('areGroupMemberVisible');
            $table->string('IsGroupChat');
            $table->unsignedBigInteger('UpdatedBy');
            $table->timestamp('UpdatedDate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};
