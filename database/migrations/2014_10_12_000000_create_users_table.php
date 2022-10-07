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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement(); // id es primary key
            $table->string('nombre', 30);
            $table->string('apellidos', 70);
            $table->string('correo', 100);
            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('id')->on('roles');
            $table->boolean('google')->default(false);
            $table->string('imagen', 100)->nullable();
            $table->string('password', 100);
            $table->integer('cantidad_reportes')->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
