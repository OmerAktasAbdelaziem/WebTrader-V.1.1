<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('clients', 'password_text')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('password_text')->nullable()->after('password');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clients', 'password_text')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('password_text');
            });
        }
    }
};
