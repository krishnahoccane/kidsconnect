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
        Schema::create('subscribers_kids', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('MainSubscriberId')->nullable();//null
            $table ->string('FirstName')->nullable();
            $table ->string('LastName')->nullable();
            $table ->string('email')->unique();
            $table ->date('Dob');
            $table ->smallInteger('Gender')->nullable();
            $table ->string('PhoneNumber');
            $table ->string('SSN');
            $table ->string('Password');
            $table ->smallInteger('LoginType');//fetch from form //null
            $table ->string('About')->nullable();
            $table ->string('Address')->nullable();
            $table ->string('ProfileImage')->nullable();
            $table ->string('Keywords')->nullable();
            $table ->enum('ProfileStatus',[0,1,2]);//enum 0 / 1 ( default 0) from admin
            $table ->smallInteger('IsApproved');//enum 0 / 1 ( default 0) default
            $table ->date('AccessApprovedOn');
            $table ->string('AccessApprovedBy');//from admin Table FK
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers_kids');
    }
};
