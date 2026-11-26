<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registros_aulas', function (Blueprint $table) {
            $table->id();
            // Cada aula pertence a uma oferta específica de disciplina.
            $table->foreignId('oferta_disciplina_id')->constrained('oferta_disciplinas')->restrictOnDelete();
            $table->date('data_aula');
            $table->unsignedInteger('horas_realizadas');
            $table->string('tipo_atividade', 30);
            $table->text('conteudo_ministrado');
            $table->timestamps();
            $table->index(['oferta_disciplina_id', 'data_aula']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('registros_aulas');
    }
};
