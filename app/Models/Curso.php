<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// Importa o tipo de relacionamento um-para-muitos.
use Illuminate\Database\Eloquent\Relations\HasMany;


class Curso extends Model
{
    protected $fillable = [
        'nome',
        'tipo_curso',
        'codigo_estrutura',
        'turno',
        'periodo_vigencia',
        'ativo',
        'prazo_minimo',
        'prazo_maximo',
        'carga_horaria_total',
        'quantidade_modulos',
        'resolucao',
        'projeto_pedagogico',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];



    
    /**
     * RELACIONAMENTO: CURSO POSSUI VÁRIOS MÓDULOS
     *
     * hasMany():
     * Informa que um curso pode possuir
     * vários módulos curriculares.
     *
     * Exemplo:
     * Técnico em Cuidados de Idosos
     *   - UNIDADE CURRICULAR I
     *   - UNIDADE CURRICULAR II
     *
     * O Laravel utiliza a coluna curso_id
     * da tabela modulos para encontrar
     * os módulos vinculados ao curso.
     */
    public function modulos(): HasMany
    {
        return $this->hasMany(Modulo::class);
    }

}
