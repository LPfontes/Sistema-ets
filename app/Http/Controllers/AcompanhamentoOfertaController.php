<?php

namespace App\Http\Controllers;

use App\Models\OfertaCurso;

/**
 * Acompanhamento da execução de cada oferta de curso.
 * Somente disciplinas explicitamente vinculadas à oferta entram nos totais.
 */
class AcompanhamentoOfertaController extends Controller
{
    public function show(OfertaCurso $ofertaCurso)
    {
        // Carrega as disciplinas, docentes e períodos sem consultas repetidas.
        $ofertaCurso->load([
            'curso', 'semestre', 'semestreFinal',
            'ofertasDisciplinas.disciplina',
            'ofertasDisciplinas.docente',
            'ofertasDisciplinas.semestre',
        ]);

        // A carga horária da oferta é a soma das disciplinas a ela atribuídas.
        $disciplinas = $ofertaCurso->ofertasDisciplinas
            ->sortBy(fn ($item) => ($item->semestre?->identificador ?? '') . '|' . ($item->disciplina?->nome ?? ''));

        $previstas = (float) $disciplinas->sum('carga_horaria_prevista');
        $cumpridas = (float) $disciplinas->sum('carga_horaria_cumprida');
        $restantes = max(0, $previstas - $cumpridas);
        $percentual = $previstas > 0 ? min(100, ($cumpridas / $previstas) * 100) : 0;

        // Sem disciplinas vinculadas, não é possível atestar integralização.
        $integralizada = $previstas > 0 && $restantes <= 0;

        return view('ofertas_cursos.acompanhamento', compact(
            'ofertaCurso', 'disciplinas', 'previstas', 'cumpridas',
            'restantes', 'percentual', 'integralizada'
        ));
    }
}
