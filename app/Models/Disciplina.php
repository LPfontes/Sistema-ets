<?php

namespace App\Models;

// Classe base utilizada pelos Models do Laravel.
use Illuminate\Database\Eloquent\Model;

// Representa um relacionamento de um registro para vários.
use Illuminate\Database\Eloquent\Relations\HasMany;

// Classe que representa a tabela "disciplinas".
class Disciplina extends Model
{
    /**
     * Campos que podem receber dados
     * durante o cadastro ou atualização.
     *
     * O Laravel utiliza essa lista nos
     * métodos create() e update().
     */
    
/**
 * CAMPOS PERMITIDOS PARA CADASTRO E ATUALIZAÇÃO
 *
 * O Laravel utiliza esta lista para saber
 * quais informações podem ser salvas usando
 * os métodos create() e update().
 */
    protected $fillable = [

        // Nome da disciplina.
        'nome',

        // Carga horária total da disciplina.
        'carga_horaria',

        // Modalidade de ensino: EAD ou Presencial.
        'tipo',

        // Categoria: Regular ou Complementar.
        'categoria',

        // Identificador do curso ao qual pertence.
        'curso_id',

        // Identificador do módulo curricular.
        // Pode ser nulo para disciplinas complementares.
        'modulo_id',

    ];


    /**
     * RELACIONAMENTO COM CURSO
     *
     * Uma disciplina pertence a um curso.
     *
     * Exemplo:
     * Informática Básica pertence ao curso
     * Técnico em Cuidados de Idosos.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * RELACIONAMENTO COM MÓDULO
     *
     * Uma disciplina pode pertencer
     * a um módulo curricular.
     *
     * Exemplo:
     * Informática Básica pertence à
     * UNIDADE CURRICULAR I.
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }


    /**
 * RELACIONAMENTO: DISCIPLINA -> OFERTAS
 *
 * Uma disciplina pode ser ofertada várias vezes,
 * em semestres diferentes e com docentes diferentes.
 *
 * hasMany indica um relacionamento de um para muitos.
 */
        public function ofertas(): HasMany
        {
            // A tabela oferta_disciplinas utiliza disciplina_id
            // para identificar a disciplina relacionada.
            return $this->hasMany(OfertaDisciplina::class, 'disciplina_id');
        }
}
