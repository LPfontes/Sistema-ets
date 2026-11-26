<?php

// Define o namespace onde o Model está localizado.
namespace App\Models;

// Importa a classe base dos Models do Laravel.
use Illuminate\Database\Eloquent\Model;


// Importa o tipo de relacionamento "um para muitos".
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * MODEL: SEMESTRE
 *
 * Representa um semestre letivo no Sistema ETS/UFPB.
 *
 * Exemplos:
 * 2026.1
 * 2026.2
 * 2027.1
 *
 * Cada semestre poderá ter uma lista de
 * disciplinas ofertadas, que será implementada
 * posteriormente por meio de relacionamentos.
 */
class Semestre extends Model
{
    /*
     * FILLABLE
     *
     * Define quais campos podem ser preenchidos
     * automaticamente pelo Laravel.
     *
     * O identificador será informado pelo usuário
     * no formulário de cadastro de semestres.
     */
    protected $fillable = [
        'identificador',
    ];




    /**
     * RELACIONAMENTO: SEMESTRE -> OFERTAS DE DISCIPLINAS
     *
     * Um semestre pode possuir várias disciplinas ofertadas.
     *
     * Exemplo:
     * Semestre 2026.2
     *   - Informática Básica
     *   - Informática Complementar
     *
     * Cada oferta está associada a uma disciplina
     * e poderá ter um docente responsável.
     */
    public function ofertasDisciplinas(): HasMany
    {
        // hasMany indica que um semestre possui várias ofertas.
        //
        // O campo semestre_id na tabela oferta_disciplinas
        // identifica a qual semestre cada oferta pertence.
        return $this->hasMany(OfertaDisciplina::class, 'semestre_id');
    }


}
