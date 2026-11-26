<?php

// Importa a classe responsável pelas migrations.
use Illuminate\Database\Migrations\Migration;

// Importa a classe utilizada para definir as colunas.
use Illuminate\Database\Schema\Blueprint;

// Importa a classe responsável por criar tabelas.
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION: SEMESTRES
 *
 * Cria a tabela "semestres" no banco MySQL.
 *
 * Requisito US3:
 * - Identificador do semestre (ex.: 2026.2)
 * - Lista das disciplinas ofertadas
 *
 * A lista de disciplinas será implementada
 * posteriormente por meio de relacionamentos.
 */
return new class extends Migration
{
    /**
     * UP
     *
     * Executado quando usamos artisan migrate.
     */
    public function up(): void
    {
        // Cria a tabela semestres.
        Schema::create('semestres', function (Blueprint $table) {

            /*
             * ID
             *
             * Identificador único e automático
             * de cada semestre cadastrado.
             */
            $table->id();

            /*
             * IDENTIFICADOR DO SEMESTRE
             *
             * Exemplos:
             * 2026.1
             * 2026.2
             * 2027.1
             *
             * unique():
             * Impede cadastrar o mesmo semestre
             * mais de uma vez no sistema.
             */
            $table->string('identificador', 6)->unique();

            /*
             * DATAS AUTOMÁTICAS
             *
             * created_at:
             * Data de criação do registro.
             *
             * updated_at:
             * Data da última atualização.
             */
            $table->timestamps();
        });
    }

    /**
     * DOWN
     *
     * Remove a tabela semestres quando
     * a migration for revertida.
     */
    public function down(): void
    {
        Schema::dropIfExists('semestres');
    }
};
