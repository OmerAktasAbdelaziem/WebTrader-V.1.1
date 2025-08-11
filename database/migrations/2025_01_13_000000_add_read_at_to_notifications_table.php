<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReadAtToNotificationsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('notifications', 'read_at')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->timestamp('read_at')->nullable()->after('text');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('notifications', 'read_at')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('read_at');
            });
        }
    }
}
