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
        Schema::create('trade_master_client_connections', function (Blueprint $table) {
            $table->id();

            $table->integer('userId')->nullable();
            
            $table->integer('masterId');
            $table->integer('clientId');
            $table->string('lotSize')->nullable();
            $table->string('symbolKeyword')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_master_client_connections');
    }
};
