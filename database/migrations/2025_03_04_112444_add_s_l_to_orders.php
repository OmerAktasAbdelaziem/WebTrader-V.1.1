<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSLToOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 's_l')) {
                $table->unsignedDouble('s_l')->nullable();
            }

            if (!Schema::hasColumn('orders', 's_p')) {
                $table->unsignedDouble('s_p')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 's_l')) {
                $table->dropColumn('s_l');
            }

            if (Schema::hasColumn('orders', 's_p')) {
                $table->dropColumn('s_p');
            }
        });
    }
}
