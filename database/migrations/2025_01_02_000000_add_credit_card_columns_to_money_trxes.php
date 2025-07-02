<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditCardColumnsToMoneyTrxes extends Migration
{
    public function up()
    {
        Schema::table('money_trxes', function (Blueprint $table) {
            $table->json('credit_card_details')->nullable();
        });
    }

    public function down()
    {
        Schema::table('money_trxes', function (Blueprint $table) {
            $table->dropColumn('credit_card_details');
        });
    }
}
