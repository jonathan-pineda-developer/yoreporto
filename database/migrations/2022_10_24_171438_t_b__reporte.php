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
            $table->string ('id', 10)->primary();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('titulo');
            $table->string('descripcion');
            $table->timestamps();
            $table->float('localizacion');
            $table->string('imagen');
            $table->string('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('TB_Categoria')->onDelete('cascade');
            $table->string('estado_id');
            $table->foreign('estado_id')->references('id')->on('TB_Estado_Reporte')->onDelete('cascade');
        
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
