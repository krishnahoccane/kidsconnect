<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('MainSubscribeId');
            $table->string('RoleId');
            $table->string('Name');
            $table->string('Breed')->nullable();
            $table->enum('gender', [0, 1, 2])->default(0);
            $table->date('Dob')->nullable();
            $table->string('Description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
