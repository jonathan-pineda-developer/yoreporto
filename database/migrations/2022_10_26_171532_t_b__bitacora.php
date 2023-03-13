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
        Schema::create('TB_Bitacora', function (Blueprint $table) {
            $table->id();
            $table->string('operation');
            $table->string('ute');
            $table->string('reporte_id');
            $table->string('modified_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::dropIfExists('TB_Bitacora');
    }

    


};