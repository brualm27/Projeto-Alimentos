<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaloriasToAlimentosTable extends Migration
{
    public function up()
    {
       
        Schema::table('alimentos', function (Blueprint $table) {
            $table->integer('calorias')->default(0);  
        });
    }

    public function down()
    {
       
        Schema::table('alimentos', function (Blueprint $table) {
            $table->dropColumn('calorias');
        });
    }
}
