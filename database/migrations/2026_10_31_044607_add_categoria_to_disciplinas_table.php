<?php

// Classe responsável pelas migrations do Laravel.
use Illuminate\Database\Migrations\Migration;

// Permite definir e modificar colunas de tabelas.
use Illuminate\Database\Schema\Blueprint;

// Permite executar alterações no banco de dados.
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MÉTODO UP
     *
     * Executado quando usamos artisan migrate.
     *
     * Adiciona a coluna categoria à tabela
     * disciplinas que já existe no banco.
     */
    public function up(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {

            /**
             * CATEGORIA DA DISCIPLINA
             *
             * Valores permitidos na aplicação:
             * - Regular
             * - Complementar
             *
             * O valor padrão será Regular.
             *
             * Isso também permite que registros
             * já existentes recebam uma categoria.
             */
            $table->string('categoria', 20)
                  ->default('Regular');

        });
    }

    /**
     * MÉTODO DOWN
     *
     * Define como desfazer esta migration.
     *
     * Remove somente a coluna categoria,
     * sem excluir a tabela disciplinas.
     */
    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {

            // Remove a coluna categoria.
            $table->dropColumn('categoria');

        });
    }
};
