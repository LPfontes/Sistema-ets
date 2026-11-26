<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Docente;
use App\Models\OfertaCurso;
use App\Models\OfertaDisciplina;
use App\Models\Semestre;
use Illuminate\Http\Request;

/**
 * Extratos calculados com base nas ofertas de disciplinas.
 * Horas cumpridas são declaradas manualmente, nunca inferidas.
 */
class ExtratoController extends Controller
{
    /** Soma as horas de um conjunto de ofertas. */
    private function totais($ofertas): array
    {
        $previstas = $ofertas->sum('carga_horaria_prevista');
        $cumpridas = $ofertas->sum('carga_horaria_cumprida');
        return ['previstas' => $previstas, 'cumpridas' => $cumpridas,
            'restantes' => max(0, $previstas - $cumpridas)];
    }

    /** Agrupa por semestre, respeitando filtros opcionais de curso e intervalo. */
    public function semestre(Request $request)
    {
        $filtros = $request->validate([
            'curso_id' => 'nullable|exists:cursos,id',
            'oferta_curso_id' => 'nullable|exists:oferta_cursos,id',
            'inicio' => ['nullable', 'regex:/^\d{4}\.[12]$/'],
            'fim' => ['nullable', 'regex:/^\d{4}\.[12]$/'],
        ]);
        $consulta = OfertaDisciplina::with(['semestre', 'disciplina.curso', 'docente', 'ofertaCurso']);
        if (!empty($filtros['curso_id'])) {
            $consulta->whereHas('disciplina', fn ($q) => $q->where('curso_id', $filtros['curso_id']));
        }
        if (!empty($filtros['oferta_curso_id'])) {
            $consulta->where('oferta_curso_id', $filtros['oferta_curso_id']);
        }
        if (!empty($filtros['inicio']) || !empty($filtros['fim'])) {
            $consulta->whereHas('semestre', function ($q) use ($filtros) {
                if (!empty($filtros['inicio'])) $q->where('identificador', '>=', $filtros['inicio']);
                if (!empty($filtros['fim'])) $q->where('identificador', '<=', $filtros['fim']);
            });
        }
        $ofertas = $consulta->get();
        $linhas = $ofertas->groupBy(fn ($o) => $o->semestre?->identificador ?? 'Sem período')
            ->sortKeys()->map(fn ($grupo, $periodo) => [
                'periodo' => $periodo, 'totais' => $this->totais($grupo), 'ofertas' => $grupo,
            ]);
        return view('extratos.semestre', [
            'linhas' => $linhas, 'totais' => $this->totais($ofertas),
            'cursos' => Curso::orderBy('nome')->get(),
            'ofertasCursos' => OfertaCurso::with('curso')->latest()->get(),
            'filtros' => $filtros,
        ]);
    }

    /** Extrato por professor com discriminação de todas as disciplinas. */
    public function docente(Request $request)
    {
        $filtros = $request->validate([
            'docente_id' => 'nullable|exists:docentes,id',
            'inicio' => ['nullable', 'regex:/^\d{4}\.[12]$/'],
            'fim' => ['nullable', 'regex:/^\d{4}\.[12]$/'],
        ]);
        $consulta = OfertaDisciplina::with(['semestre', 'disciplina.curso', 'ofertaCurso', 'docente']);
        if (!empty($filtros['docente_id'])) {
            $consulta->where('docente_id', $filtros['docente_id']);
        } else {
            // Não misturar ofertas sem docente atribuído aos totais de professores.
            $consulta->whereNotNull('docente_id');
        }
        if (!empty($filtros['inicio']) || !empty($filtros['fim'])) {
            $consulta->whereHas('semestre', function ($q) use ($filtros) {
                if (!empty($filtros['inicio'])) $q->where('identificador', '>=', $filtros['inicio']);
                if (!empty($filtros['fim'])) $q->where('identificador', '<=', $filtros['fim']);
            });
        }
        $ofertas = $consulta->get();
        $linhas = $ofertas->groupBy('docente_id')->map(fn ($grupo) => [
            'docente' => $grupo->first()->docente,
            'totais' => $this->totais($grupo),
            'ofertas' => $grupo->sortBy(fn ($o) => $o->semestre?->identificador),
        ])->sortBy(fn ($item) => $item['docente']?->nome);
        return view('extratos.docente', [
            'linhas' => $linhas, 'totais' => $this->totais($ofertas),
            'docentes' => Docente::orderBy('nome')->get(), 'filtros' => $filtros,
        ]);
    }
}
