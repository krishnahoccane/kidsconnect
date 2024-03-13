<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedTinyInteger('RoleId')->default(0);
            $table->unsignedTinyInteger('ProfileStatus')->default(0);
            $table->date('ApprovedOn')->nullable()->default(null);
            $table->string('ApprovedBy')->nullable();
            $table->date('DeniedOn')->nullable()->default(null);
            $table->string('DeniedBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
