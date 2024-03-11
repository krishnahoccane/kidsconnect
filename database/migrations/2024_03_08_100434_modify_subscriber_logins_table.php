<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subscriber_logins', function (Blueprint $table) {
            $table->unsignedSmallInteger('MainSubscriberId')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('subscriber_logins', function (Blueprint $table) {
            $table->smallInteger('MainSubscriberId')->change();
        });
    }
};
