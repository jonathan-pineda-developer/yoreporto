<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TB_Informe', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('id_reporte', 36);
            $table->foreign('id_reporte')->references('id')->on('TB_Reporte');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TB_Informe');
    }
};
