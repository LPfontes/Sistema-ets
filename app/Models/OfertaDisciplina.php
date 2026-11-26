<?php

namespace App\Models;

// Model é a classe base do Eloquent ORM do Laravel.
use Illuminate\Database\Eloquent\Model;

// Define os tipos de relacionamentos utilizados.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfertaDisciplina extends Model
{
    /*
     * NOME DA TABELA
     *
     * Informa ao Laravel qual tabela do banco
     * de dados corresponde a este Model.
     */
    protected $table = 'oferta_disciplinas';

    /*
     * CAMPOS PERMITIDOS
     *
     * Define quais atributos podem receber
     * valores através dos métodos create()
     * e update() do Eloquent.
     */
    protected $fillable = [
        'disciplina_id',
        'semestre_id',
        'docente_id',
        'carga_horaria_prevista',
        'carga_horaria_cumprida',
        'horas_legadas',
        'oferta_curso_id',
    ];

    /*
     * RELACIONAMENTO COM DISCIPLINA
     *
     * Cada oferta pertence a uma disciplina.
     *
     * Exemplo:
     * Oferta 1 -> Informática Básica.
     */
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    /*
     * RELACIONAMENTO COM SEMESTRE
     *
     * Cada oferta pertence a um semestre letivo.
     *
     * Exemplo:
     * Oferta 1 -> 2026.2.
     */
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }

    /*
     * RELACIONAMENTO COM DOCENTE
     *
     * Cada oferta pode ter um professor responsável.
     *
     * O docente pode ser definido posteriormente,
     * pois docente_id aceita valores nulos.
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }
    /** Aulas registradas para esta oferta de disciplina. */
    public function registrosAulas(): HasMany
    {
        return $this->hasMany(RegistroAula::class, 'oferta_disciplina_id');
    }

    /** Oferta de curso (turma/coorte) à qual a disciplina está vinculada. */
    public function ofertaCurso(): BelongsTo
    {
        return $this->belongsTo(OfertaCurso::class, 'oferta_curso_id');
    }
}
