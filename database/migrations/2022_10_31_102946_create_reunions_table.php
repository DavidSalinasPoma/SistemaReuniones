<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReunionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reunions', function (Blueprint $table) {
            $table->id();
            $table->text('motivo')->unique();;
            $table->text('asunto');
            $table->string('prioridad');
            $table->string('fecha_reunion');
            $table->integer('estado')->default(1);
            $table->foreignId('usuarios_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('estado_reunion')->default(0);
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
        Schema::dropIfExists('reunions');
    }
}
