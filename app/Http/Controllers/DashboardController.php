<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Disciplina;
use App\Models\Docente;
use App\Models\OfertaCurso;
use App\Models\Semestre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/** Painel de indicadores e acompanhamento das ofertas, sem modificar registros. */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Valida filtros para impedir consultas com identificadores inválidos.
        $filtros = $request->validate([
            'curso_id' => ['nullable', 'integer', Rule::exists('cursos', 'id')],
            'oferta_id' => ['nullable', 'integer', Rule::exists('oferta_cursos', 'id')],
            'semestre_id' => ['nullable', 'integer', Rule::exists('semestres', 'id')],
        ]);

        $cursoId = $filtros['curso_id'] ?? null;
        $ofertaId = $filtros['oferta_id'] ?? null;
        $semestreId = $filtros['semestre_id'] ?? null;

        // Confere se a oferta realmente pertence ao curso escolhido.
        if ($ofertaId && $cursoId && !OfertaCurso::whereKey($ofertaId)->where('curso_id', $cursoId)->exists()) {
            $ofertaId = null;
        }

        // A CH oficial pertence ao cadastro do curso e não depende de oferta ou semestre.
        $cursoSelecionado = $cursoId ? Curso::find($cursoId) : null;
        $cursos = Curso::orderBy('nome')->get(['id', 'nome']);
        $semestres = Semestre::orderByDesc('identificador')->get(['id', 'identificador']);
        $ofertas = OfertaCurso::with(['curso', 'semestre'])
            ->when($cursoId, fn ($q) => $q->where('curso_id', $cursoId))
            ->orderByDesc('id')->get();

        // Cada oferta de disciplina entra uma única vez na soma.
        // As horas cumpridas já incluem registros de aulas e horas legadas.
        $consulta = DB::table('oferta_disciplinas as od')
            ->join('oferta_cursos as oc', 'oc.id', '=', 'od.oferta_curso_id')
            ->when($cursoId, fn ($q) => $q->where('oc.curso_id', $cursoId))
            ->when($ofertaId, fn ($q) => $q->where('oc.id', $ofertaId))
            ->when($semestreId, fn ($q) => $q->where('od.semestre_id', $semestreId));

        $horas = (clone $consulta)->selectRaw(
            'COALESCE(SUM(od.carga_horaria_prevista), 0) as previstas, COALESCE(SUM(od.carga_horaria_cumprida), 0) as cumpridas, COUNT(*) as disciplinas_ofertadas'
        )->first();

        $previstas = (float) $horas->previstas;
        $cumpridas = (float) $horas->cumpridas;
        $restantes = max(0, $previstas - $cumpridas);
        $percentual = $previstas > 0 ? min(100, ($cumpridas / $previstas) * 100) : 0;

        // As ofertas recentes são apenas informativas e usam rotas já existentes.
        $ofertasRecentes = OfertaCurso::with(['curso', 'semestre'])
            ->orderByDesc('id')->limit(5)->get();

        return view('dashboard', [
            'totalCursos' => Curso::count(),
            'totalDisciplinas' => Disciplina::count(),
            'totalDocentes' => Docente::count(),
            'totalOfertas' => OfertaCurso::count(),
            'cursos' => $cursos,
            'semestres' => $semestres,
            'ofertas' => $ofertas,
            'ofertasRecentes' => $ofertasRecentes,
            'cursoId' => $cursoId,
            'cursoSelecionado' => $cursoSelecionado,
            'ofertaId' => $ofertaId,
            'semestreId' => $semestreId,
            'previstas' => $previstas,
            'cumpridas' => $cumpridas,
            'restantes' => $restantes,
            'percentual' => $percentual,
            'disciplinasOfertadas' => (int) $horas->disciplinas_ofertadas,
        ]);
    }
}
