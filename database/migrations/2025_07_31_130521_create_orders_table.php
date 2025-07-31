<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('broker_id')->nullable();
            $table->unsignedBigInteger('currency')->nullable(); 
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('open_price', 12, 6)->nullable();
            $table->decimal('close_price', 12, 6)->nullable();
            $table->decimal('pnl', 12, 6)->nullable();
            $table->decimal('s_l', 12, 6)->nullable();
            $table->decimal('s_p', 12, 6)->nullable();
            $table->string('type')->nullable(); // buy/sell
            $table->string('status')->default('active');
            $table->string('ref_currency')->nullable();
            $table->decimal('required_margin', 12, 2)->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
