<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Método up()
     *
     * Executado quando usamos:
     * php artisan migrate
     *
     * Adiciona os campos necessários à tabela
     * disciplinas, que já existe no banco.
     */
    public function up(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {

            // Nome da disciplina.
            // Exemplo: Informática Básica.
            $table->string('nome');

            // Carga horária da disciplina em horas.
            // Exemplo: 60.
            // Não permite valores negativos.
            $table->unsignedInteger('carga_horaria');

            // Modalidade de ensino da disciplina.
            // Os valores EAD e Presencial serão
            // validados posteriormente no Controller.
            $table->string('tipo', 20);

            // Relaciona a disciplina com um curso.
            //
            // Exemplo:
            // Uma disciplina pertence ao curso
            // Técnico em Cuidados de Idosos.
            //
            // Impede excluir um curso que ainda
            // possui disciplinas vinculadas.
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->restrictOnDelete();

            // Relaciona a disciplina com um módulo.
            //
            // Exemplo:
            // UNIDADE CURRICULAR I.
            //
            // O módulo pode ser informado depois,
            // por isso utilizamos nullable().
            $table->foreignId('modulo_id')
                  ->nullable()
                  ->constrained('modulos')
                  ->restrictOnDelete();

        });
    }

    /**
     * Método down()
     *
     * Define como desfazer esta migration,
     * caso seja necessário um rollback.
     *
     * Remove somente os campos adicionados
     * por esta migration.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {

            // Remove as chaves estrangeiras.
            $table->dropForeign(['curso_id']);
            $table->dropForeign(['modulo_id']);

            // Remove as colunas adicionadas.
            $table->dropColumn([
                'nome',
                'carga_horaria',
                'tipo',
                'curso_id',
                'modulo_id',
            ]);

        });
    }
};
