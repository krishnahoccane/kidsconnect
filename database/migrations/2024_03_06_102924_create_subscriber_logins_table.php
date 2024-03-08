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
            $table ->smallInteger('MainSubscriberId');//null
            $table ->smallInteger('RoleId');// fetch from form // null
            $table ->string('FirstName');
            $table ->string('LastName');
            $table ->string('email')->unique();
            $table ->date('Dob');
            $table ->smallInteger('Gender');
            $table ->string('PhoneNumber');
            $table ->string('SSN');
            $table ->smallInteger('IsMain');//filled by subscriber table use as FK //null
            $table ->string('Password');
            $table ->smallInteger('LoginType');//fetch from form //null
            $table ->string('About');
            $table ->string('Address');
            $table ->string('ProfileImage');
            $table ->string('Keywords');
            $table->timestamps();//default
            $table ->smallInteger('ProfileStatus');//enum 0 / 1 ( default 0) from admin
            $table ->smallInteger('IsApproved');//enum 0 / 1 ( default 0) default
            $table ->date('ApprovedOn');
            $table ->string('ApprovedBy');//from admin Table FK
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
