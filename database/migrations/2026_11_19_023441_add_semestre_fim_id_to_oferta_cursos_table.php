<?php

// Classe base utilizada para executar migrations.
use Illuminate\Database\Migrations\Migration;

// Permite definir e modificar colunas de tabelas.
use Illuminate\Database\Schema\Blueprint;

// Permite acessar a estrutura do banco de dados.
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION: ADICIONAR SEMESTRE FINAL À OFERTA DE CURSO
 *
 * Cada oferta poderá informar:
 * - Semestre inicial: campo semestre_id já existente.
 * - Semestre final: novo campo semestre_fim_id.
 *
 * Exemplo:
 * Curso: Técnico em Cuidados de Idosos
 * Início: 2026.2
 * Fim: 2028.1
 *
 * O novo campo será opcional inicialmente,
 * para preservar os registros já cadastrados.
 */
return new class extends Migration
{
    /**
     * Adiciona a nova coluna à tabela oferta_cursos.
     */
    public function up(): void
    {
        Schema::table('oferta_cursos', function (Blueprint $table) {

            /*
             * SEMESTRE FINAL DA OFERTA
             *
             * foreignId():
             * Cria uma coluna para armazenar o ID
             * do período letivo final.
             *
             * nullable():
             * Permite que ofertas antigas continuem
             * cadastradas sem um período final.
             *
             * constrained('semestres'):
             * Relaciona o campo à tabela semestres.
             *
             * restrictOnDelete():
             * Impede excluir um semestre utilizado
             * como período final de uma oferta.
             */
            $table->foreignId('semestre_fim_id')
                  ->nullable()
                  ->constrained('semestres')
                  ->restrictOnDelete();

        });
    }

    /**
     * Desfaz somente a alteração desta migration.
     *
     * Não exclui a tabela oferta_cursos.
     */
    public function down(): void
    {
        Schema::table('oferta_cursos', function (Blueprint $table) {

            // Remove a chave estrangeira e a coluna criada.
            $table->dropConstrainedForeignId('semestre_fim_id');

        });
    }
};
