<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBoloInteressados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bolo_interessados')){
            Schema::create('bolo_interessados', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->boolean('notificado')->default(false);
                $table->integer('bolo_id')->unsigned();
                $table->foreign('bolo_id')->references('id')->on('bolos')->onDelete('restrict');
                $table->timestamps();
                $table->softDeletes();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bolo_interessados');
    }
}
