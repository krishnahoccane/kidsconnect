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
        Schema::create('subscriber_logins', function (Blueprint $table) {
            $table->id();
            $table ->smallInteger('MainSubscriberId');
            $table ->smallInteger('RoleId');
            $table ->string('FirstName');
            $table ->string('LastName');
            $table ->string('email')->unique();
            $table ->date('Dob');
            $table ->smallInteger('Gender');
            $table ->string('PhoneNumber');
            $table ->string('SSN');
            $table ->smallInteger('IsMain');
            $table ->string('Password');
            $table ->smallInteger('LoginType');
            $table ->string('About');
            $table ->string('Address');
            $table ->string('ProfileImage');
            $table ->string('Keywords');
            $table->timestamps();
            $table ->smallInteger('ProfileStatus');
            $table ->smallInteger('IsApproved');
            $table ->date('ApprovedOn');
            $table ->string('ApprovedBy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_logins');
    }
};
