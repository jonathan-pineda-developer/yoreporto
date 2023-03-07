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
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('titulo');
            $table->string('descripcion');
            $table->string('imagen')->nullable();
            $table->double('latitud');
            $table->double('longitud');
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('TB_Categoria');
            $table->string('estado')->default('En espera');
            $table->timestamps();
            /* $table->unsignedBigInteger('id_marcador')->nullable();
            $table->foreign('id_marcador')->references('id')->on('TB_Marcador');*/
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
