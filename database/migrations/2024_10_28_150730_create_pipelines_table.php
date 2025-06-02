<?php

use App\Models\Pipeline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePipelinesTable extends Migration
{
    public function up()
    {
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Pipeline::create(['name' => 'Lead']);
    }

    public function down()
    {
        Schema::dropIfExists('pipelines');
    }
}
