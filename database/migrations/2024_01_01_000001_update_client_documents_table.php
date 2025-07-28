<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientDocumentsTable extends Migration
{
    public function up()
    {
        Schema::table('client_documents', function (Blueprint $table) {
            // Add new fields for document upload functionality
            $table->string('type')->nullable()->after('client_id'); // 'kyc' or 'other'
            $table->string('original_name')->nullable()->after('type');
            $table->string('file_path')->nullable()->after('original_name');
            $table->bigInteger('file_size')->nullable()->after('file_path');
            $table->string('mime_type')->nullable()->after('file_size');
            $table->timestamp('uploaded_at')->nullable()->after('mime_type');
        });
    }

    public function down()
    {
        Schema::table('client_documents', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'original_name',
                'file_path',
                'file_size',
                'mime_type',
                'uploaded_at'
            ]);
        });
    }
}
