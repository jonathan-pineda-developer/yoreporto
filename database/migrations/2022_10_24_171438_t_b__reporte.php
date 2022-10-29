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
        Schema::create('TB_Reporte', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('titulo');
            $table->string('descripcion');
            $table->timestamps();
            $table->float('localizacion')->nullable();
            $table->string('imagen')->nullable();
            $table->string('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('TB_Categoria');
            $table->string('estado_id')->nullable();
            $table->foreign('estado_id')->references('id')->on('TB_Estado_Reporte');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TB_reporte');
    }
};
