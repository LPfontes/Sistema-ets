<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Registro auditável de uma aula efetivamente realizada. */
class RegistroAula extends Model
{
    protected $table = 'registros_aulas';
    protected $fillable = ['oferta_disciplina_id', 'data_aula', 'horas_realizadas', 'tipo_atividade', 'conteudo_ministrado'];
    protected $casts = ['data_aula' => 'date'];

    public function ofertaDisciplina(): BelongsTo
    {
        return $this->belongsTo(OfertaDisciplina::class, 'oferta_disciplina_id');
    }
}
