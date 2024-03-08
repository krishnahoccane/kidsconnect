<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscriber_logins', function (Blueprint $table) {
            $table->unsignedSmallInteger('RoleId')->nullable()->change();
            $table->enum('Gender', [0, 1, 2])->default(0)->change();
            $table->unsignedSmallInteger('IsMain')->nullable()->change();
            $table->unsignedSmallInteger('LoginType')->nullable()->change();
            $table->string('Keywords')->nullable()->change();
            $table->enum('ProfileStatus', [0, 1])->default(0)->change();
            $table->enum('IsApproved', [0, 1])->default(0)->change();
            $table->date('ApprovedOn')->nullable()->change();
            $table->string('ApprovedBy')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('subscriber_logins', function (Blueprint $table) {
            $table ->smallInteger('RoleId')->change();
            $table ->smallInteger('Gender')->change();
            $table ->smallInteger('IsMain')->change();
            $table ->smallInteger('LoginType')->change();
            $table ->smallInteger('ProfileStatus')->change();
            $table ->smallInteger('IsApproved')->change();
            $table ->string('Keywords')->change();
            $table ->date('ApprovedOn')->change();
            $table ->string('ApprovedBy')->change();
        });
    }
};
