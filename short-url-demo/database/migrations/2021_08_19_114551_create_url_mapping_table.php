<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_mapping', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->comment('原始網址代號');
            $table->string('long_url', 2083)->comment('原始網址');
            $table->string('short_url', 40)->comment('短網址');
            $table->dateTime('create_time')->comment('創建時間');
            $table->index('code');
            $table->index('short_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_mapping');
    }
}
