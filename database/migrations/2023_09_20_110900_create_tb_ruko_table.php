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
        Schema::create('tb_ruko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruko');
            $table->string('gambar_ruko');
            $table->string('latitude' , 50);
            $table->string('longtitude' , 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_ruko');
    }
};
