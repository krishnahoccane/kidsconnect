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
    Schema::create('sub_child_permissions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('Subschildid');
        $table->unsignedBigInteger('SubscriberId');
        $table->string('Permissionname');
        $table->unsignedBigInteger('Statusid');
        $table->timestamp('Updatedby')->nullable();
        $table->timestamp('Updateddate')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_child_permissions');
    }
};
