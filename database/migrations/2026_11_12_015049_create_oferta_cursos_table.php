
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CRIAR A TABELA DE OFERTAS DE CURSOS
     *
     * Esta tabela registra quando um curso
     * será ofertado pela ETS/UFPB.
     *
     * Um mesmo curso poderá possuir
     * várias ofertas em períodos diferentes.
     */
    public function up(): void
    {
        Schema::create('oferta_cursos', function (Blueprint $table) {

            // Identificador único da oferta.
            $table->id();

            /*
             * CURSO
             *
             * Relaciona a oferta a um curso
             * existente na tabela cursos.
             *
             * restrictOnDelete impede excluir
             * um curso que possui ofertas.
             */
            $table->foreignId('curso_id')
                ->constrained('cursos')
                ->restrictOnDelete();

            /*
             * SEMESTRE INICIAL
             *
             * Indica quando o curso começará.
             *
             * Exemplo: 2026.2
             */
            $table->foreignId('semestre_id')
                ->constrained('semestres')
                ->restrictOnDelete();

            /*
             * QUANTIDADE DE TURMAS
             *
             * Informa quantas turmas serão
             * abertas nesta oferta.
             *
             * Valor padrão: 1 turma.
             */
            $table->unsignedInteger('quantidade_turmas')
                ->default(1);

            /*
             * SITUAÇÃO DA OFERTA
             *
             * Indica o estado atual:
             * - Planejada
             * - Em andamento
             * - Concluída
             */
            $table->string('situacao', 30)
                ->default('Planejada');

            /*
             * OBSERVAÇÃO
             *
             * Campo opcional para registrar
             * informações complementares.
             */
            $table->text('observacao')
                ->nullable();

            // Datas de criação e atualização.
            $table->timestamps();

        });
    }

    /**
     * DESFAZER A CRIAÇÃO DA TABELA
     *
     * Este método é utilizado pelo Laravel
     * quando a Migration é revertida.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_cursos');
    }
};
