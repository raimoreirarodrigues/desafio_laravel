<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBolo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bolos')){
            Schema::create('bolos', function (Blueprint $table) {
                $table->increments('id');
                $table->string("nome");
                $table->decimal("peso",6,2);
                $table->decimal("valor",10,2);
                $table->integer("quantidade")->default(0);
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
        Schema::dropIfExists('bolos');
    }
}
