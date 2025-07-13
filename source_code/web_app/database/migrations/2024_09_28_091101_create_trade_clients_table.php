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
        Schema::create('trade_clients', function (Blueprint $table) {
            $table->id();

            $table->integer('userId')->nullable();

            $table->string('clientTitle')->nullable();
            $table->string('clientInfo')->nullable();
            $table->string('clientTradeComment')->nullable();

            $table->string('clientSoftware')->nullable();
            $table->longText('clientToken')->nullable();

            $table->string('zeroSL')->nullable();
            $table->string('zeroTP')->nullable();
     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_clients');
    }
};
