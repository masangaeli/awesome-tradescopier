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
        Schema::create('trade_data', function (Blueprint $table) {
            $table->id();

            $table->integer('userId')->nullable();

            $table->string('tradeSource')->nullable();
            $table->integer('tradeSourceId')->nullable();
            $table->integer('isMaster')->default(0);

            $table->string('tradeClient')->nullable();
            $table->integer('tradeClientId')->default(0);
            
            $table->string('ticketId')->nullable();
            $table->string('tradeType')->nullable();
            $table->string('symbol')->nullable();
            $table->string('openPrice')->nullable();
            $table->string('slPrice')->nullable();
            $table->string('tpPrice')->nullable();

            $table->integer('copyStatus')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_data');
    }
};
