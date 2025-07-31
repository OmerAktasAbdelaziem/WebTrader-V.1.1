<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->decimal('sell_commission', 10, 4)->nullable();
            $table->decimal('buy_commission', 10, 4)->nullable();
            $table->json('is_percentage')->nullable();
            $table->decimal('ask_spread', 10, 4)->nullable();
            $table->decimal('bid_spread', 10, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('currency')->nullable();
            $table->json('leverage')->nullable();
            $table->string('symbol')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->json('size')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
};

