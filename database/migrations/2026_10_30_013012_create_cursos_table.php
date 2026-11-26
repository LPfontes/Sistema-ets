<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo_estrutura', 30);
            $table->string('turno');
            $table->string('periodo_vigencia', 6);
            $table->boolean('ativo')->default(true);
            $table->unsignedInteger('prazo_minimo')->nullable();
            $table->unsignedInteger('prazo_maximo')->nullable();
            $table->unsignedInteger('carga_horaria_total');
            $table->unsignedInteger('quantidade_modulos');
            $table->string('resolucao')->nullable();
            $table->text('projeto_pedagogico')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
