<?php

// Importa a classe responsável pelas migrations.
use Illuminate\Database\Migrations\Migration;

// Permite definir as colunas de uma tabela.
use Illuminate\Database\Schema\Blueprint;

// Permite criar e modificar tabelas no banco.
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| MIGRATION - TABELA MÓDULOS
|--------------------------------------------------------------------------
|
| Esta migration cria a tabela "modulos".
|
| Cada módulo pertence a um curso cadastrado.
|
| Exemplo:
| Curso: Técnico em Cuidados de Idosos
| Módulo: UNIDADE CURRICULAR I
| Carga horária: 420 horas
| Período de oferta: 1
|
*/

return new class extends Migration
{
    /**
     * Executa a criação da tabela no banco MySQL.
     */
    public function up(): void
    {
        // Cria a tabela chamada "modulos".
        Schema::create('modulos', function (Blueprint $table) {

            /*
             * Identificador único de cada módulo.
             *
             * O Laravel gera automaticamente
             * os valores: 1, 2, 3, ...
             */
            $table->id();

            /*
             * Identifica a qual curso o módulo pertence.
             *
             * constrained('cursos'):
             * Cria uma chave estrangeira que aponta
             * para a tabela "cursos".
             *
             * restrictOnDelete():
             * Impede excluir um curso que possua
             * módulos vinculados.
             *
             * Isso ajuda a proteger os dados acadêmicos.
             */
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->restrictOnDelete();

            /*
             * Armazena o nome do módulo.
             *
             * Exemplo:
             * UNIDADE CURRICULAR I
             */
            $table->string('nome');

            /*
             * Armazena a carga horária oficial do módulo.
             *
             * unsignedInteger():
             * Aceita números inteiros não negativos.
             *
             * Exemplo: 420 horas.
             */
            $table->unsignedInteger('carga_horaria');

            /*
             * Representa o período de oferta
             * informado na estrutura curricular.
             *
             * Exemplos:
             * 1 = primeiro período de oferta
             * 2 = segundo período de oferta
             *
             * Não representa necessariamente
             * um semestre acadêmico.
             */
            $table->unsignedInteger('periodo_oferta');

            /*
             * Cria duas colunas automáticas:
             *
             * created_at: data de criação.
             * updated_at: data da última alteração.
             */
            $table->timestamps();

        });
    }

    /**
     * Desfaz a migration, caso seja necessário
     * reverter a criação da tabela.
     */
    public function down(): void
    {
        // Remove a tabela "modulos" se ela existir.
        Schema::dropIfExists('modulos');
    }
};
