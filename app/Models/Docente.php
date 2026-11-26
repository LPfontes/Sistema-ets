<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// Importa o relacionamento "um para muitos" do Laravel.
use Illuminate\Database\Eloquent\Relations\HasMany;


/*La propriété $fillable autorise Laravel à enregistrer ces trois champs dans la base de données via Eloquent.*/
class Docente extends Model
{
    protected $fillable = [
        'nome',
        'colegiado',
        'siape',
    ];

    
    /**
     * RELACIONAMENTO: DOCENTE -> OFERTAS DE DISCIPLINAS
     *
     * Um docente pode ser responsável por várias
     * ofertas de disciplinas.
     *
     * Exemplo:
     * Professor A:
     * - Informática Básica no semestre 2026.2
     * - Informática Complementar no semestre 2027.1
     *
     * Esse relacionamento também permitirá calcular
     * futuramente a carga horária atribuída ao docente.
     */
    public function ofertasDisciplinas(): HasMany
    {
        // Busca todas as ofertas que possuem o ID
        // deste docente no campo docente_id.
        return $this->hasMany(
            OfertaDisciplina::class,
            'docente_id'
        );
    }

}
