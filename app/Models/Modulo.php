<?php

// Define o namespace do Model.
namespace App\Models;

// Importa a classe Model do Laravel.
use Illuminate\Database\Eloquent\Model;

// Importa o tipo de relacionamento belongsTo.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Importa o relacionamento de um registro para vários.
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MODEL: MODULO
 *
 * Representa um módulo curricular
 * cadastrado no Sistema ETS/UFPB.
 *
 * Cada módulo pertence a um curso.
 */
class Modulo extends Model
{
    /**
     * Define os campos que podem ser preenchidos
     * durante o cadastro ou atualização.
     *
     * O Laravel utiliza esta lista para permitir
     * operações como Modulo::create($dados).
     */
    protected $fillable = [

        // Identificador do curso ao qual o módulo pertence.
        'curso_id',

        // Nome do módulo, como UNIDADE CURRICULAR I.
        'nome',

        // Carga horária oficial do módulo, em horas.
        'carga_horaria',

        // Período de oferta do módulo: 1, 2, 3...
        'periodo_oferta',
    ];

    /**
     * RELACIONAMENTO: MÓDULO PERTENCE A CURSO
     *
     * belongsTo():
     * Informa ao Laravel que cada módulo
     * está vinculado a um curso.
     *
     * A ligação utiliza a coluna curso_id
     * da tabela modulos.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }


/**
 * RELACIONAMENTO: MÓDULO -> DISCIPLINAS
 *
 * Um módulo pode possuir várias disciplinas.
 *
 * Exemplo:
 * Módulo I:
 * - Informática Básica (60h)
 * - Anatomia (80h)
 * - Ética Profissional (40h)
 *
 * O Laravel utiliza a coluna modulo_id
 * da tabela disciplinas para encontrar
 * todas as disciplinas do módulo.
 */
public function disciplinas(): HasMany
{
    return $this->hasMany(Disciplina::class, 'modulo_id');
}

}
