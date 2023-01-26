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
            $table->uuid('id')->primary(); // id es primary key
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('email');
            $table->string('rol')->default('Ciudadano');
            $table->boolean('google')->default(false);
            $table->string('imagen')->nullable();
            $table->string('password');
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
