<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MODEL: OFERTA DE CURSO
 *
 * Representa uma oferta de curso cadastrada
 * no Sistema ETS/UFPB.
 *
 * Cada oferta pertence:
 * - A um curso;
 * - A um semestre inicial.
 *
 * Um curso poderá ser ofertado em diferentes
 * períodos letivos.
 */
class OfertaCurso extends Model
{
    /*
     * NOME DA TABELA
     *
     * Informa ao Laravel qual tabela
     * será utilizada por este Model.
     */
    protected $table = 'oferta_cursos';

    /*
     * CAMPOS PERMITIDOS PARA CADASTRO
     *
     * fillable define quais atributos podem
     * ser preenchidos utilizando create()
     * ou update().
     *
     * O ID não precisa ser informado,
     * pois é gerado automaticamente.
     */
    protected $fillable = [
        'curso_id',
        'semestre_id',
        'semestre_fim_id',
        'quantidade_turmas',
        'situacao',
        'observacao',
    ];

    /**
     * RELACIONAMENTO COM CURSO
     *
     * Uma oferta pertence a um curso.
     *
     * Exemplo:
     * Oferta iniciada em 2026.2
     * pertence ao curso Técnico em Cuidados
     * de Idosos.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * RELACIONAMENTO COM SEMESTRE
     *
     * Uma oferta pertence ao semestre
     * em que o curso começa.
     *
     * Exemplo:
     * Semestre inicial: 2026.2
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }



/**
 * RELACIONAMENTO: OFERTA -> SEMESTRE FINAL
 *
 * Uma oferta de curso possui um período letivo
 * previsto para seu encerramento.
 *
 * Exemplo:
 * Curso: Técnico em Cuidados de Idosos
 * Início: 2026.2
 * Fim: 2028.1
 *
 * belongsTo():
 * Informa ao Laravel que o semestre final
 * está cadastrado na tabela "semestres".
 *
 * O segundo argumento identifica a coluna
 * semestre_fim_id da tabela oferta_cursos.
 */
public function semestreFinal(): BelongsTo
{
    return $this->belongsTo(
        Semestre::class,
        'semestre_fim_id'
    );
}

    /** Disciplinas atribuídas especificamente a esta oferta de curso. */
    public function ofertasDisciplinas(): HasMany
    {
        return $this->hasMany(OfertaDisciplina::class, 'oferta_curso_id');
    }
}
