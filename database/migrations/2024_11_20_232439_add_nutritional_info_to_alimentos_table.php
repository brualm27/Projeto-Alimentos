<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaloriasAndNutritionalInfoToAlimentosTable extends Migration
{
    public function up()
{
    Schema::table('alimentos', function (Blueprint $table) {
        $table->text('nutritional_info')->nullable();
    });
}

public function down()
{
    Schema::table('alimentos', function (Blueprint $table) {
        $table->dropColumn('nutritional_info');
    });
}

}
