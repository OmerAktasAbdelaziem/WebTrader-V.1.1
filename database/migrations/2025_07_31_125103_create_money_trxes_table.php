<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyTrxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_trxes', function (Blueprint $table) {
        $table->id();
        $table->json('bank_details')->nullable();
        $table->unsignedBigInteger('broker_id')->nullable();
        $table->unsignedBigInteger('bank_id')->nullable();
        $table->text('comment')->nullable();
        $table->decimal('amount', 12, 2)->nullable();
        $table->string('type')->nullable();
        $table->json('usdt')->nullable();
        $table->string('receipt')->nullable();
        $table->timestamps();
    });

    }

}
