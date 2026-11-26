<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de ofertas de disciplinas.
     *
     * Cada registro representa uma disciplina
     * ofertada em determinado semestre,
     * com um docente responsável.
     */
    public function up(): void
    {
        Schema::create('oferta_disciplinas', function (Blueprint $table) {

            // Identificador único da oferta.
            $table->id();

            /*
             * DISCIPLINA
             *
             * Relaciona a oferta com uma disciplina
             * já cadastrada no sistema.
             *
             * restrictOnDelete impede excluir uma
             * disciplina que possui ofertas.
             */
            $table->foreignId('disciplina_id')
                ->constrained('disciplinas')
                ->restrictOnDelete();

            /*
             * SEMESTRE
             *
             * Indica o período letivo da oferta,
             * por exemplo: 2026.2 ou 2027.1.
             */
            $table->foreignId('semestre_id')
                ->constrained('semestres')
                ->restrictOnDelete();

            /*
             * DOCENTE
             *
             * Identifica o professor responsável
             * pela disciplina ofertada.
             *
             * nullable permite cadastrar uma oferta
             * antes de definir o professor.
             */
            $table->foreignId('docente_id')
                ->nullable()
                ->constrained('docentes')
                ->restrictOnDelete();

            /*
             * CARGA HORÁRIA PREVISTA
             *
             * Registra a quantidade de horas
             * atribuídas a esta oferta.
             *
             * Pode ser diferente da carga horária
             * padrão da disciplina, se necessário.
             */
            $table->unsignedInteger('carga_horaria_prevista');

            /*
             * DATAS DE CONTROLE
             *
             * created_at: data de criação.
             * updated_at: última atualização.
             */
            $table->timestamps();

        });
    }

    /**
     * Desfaz a criação da tabela caso
     * seja necessário reverter esta migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_disciplinas');
    }
};
