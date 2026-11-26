<?php

namespace App\Http\Controllers;

use App\Models\OfertaDisciplina;
use App\Models\RegistroAula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** Gerencia registros de aulas e atualiza automaticamente as horas cumpridas. */
class RegistroAulaController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate(['oferta_disciplina_id' => 'nullable|exists:oferta_disciplinas,id']);
        $consulta = RegistroAula::with(['ofertaDisciplina.disciplina', 'ofertaDisciplina.docente', 'ofertaDisciplina.semestre']);
        if (!empty($filtros['oferta_disciplina_id'])) {
            $consulta->where('oferta_disciplina_id', $filtros['oferta_disciplina_id']);
        }
        return view('registros_aulas.index', [
            'registros' => $consulta->orderByDesc('data_aula')->orderByDesc('id')->paginate(20)->withQueryString(),
            'ofertas' => $this->ofertas(),
            'filtros' => $filtros,
        ]);
    }

    public function create()
    {
        return view('registros_aulas.form', ['registro' => new RegistroAula(), 'ofertas' => $this->ofertas()]);
    }

    public function store(Request $request)
    {
        $dados = $this->validar($request);
        DB::transaction(function () use ($dados) {
            // Bloqueia a oferta para evitar atualizações simultâneas incorretas.
            $oferta = OfertaDisciplina::whereKey($dados['oferta_disciplina_id'])->lockForUpdate()->firstOrFail();
            $this->validarTotal($oferta, (int) $dados['horas_realizadas']);
            RegistroAula::create($dados);
            $this->recalcular($oferta);
        });
        return redirect()->route('registros-aulas.index')->with('success', 'Aula registrada e carga horária atualizada!');
    }

    public function edit(RegistroAula $registros_aula)
    {
        return view('registros_aulas.form', ['registro' => $registros_aula, 'ofertas' => $this->ofertas()]);
    }

    public function update(Request $request, RegistroAula $registros_aula)
    {
        $dados = $this->validar($request);
        DB::transaction(function () use ($dados, $registros_aula) {
            // Bloqueia as duas ofertas, se a aula for transferida de disciplina.
            $ids = array_unique([(int) $registros_aula->oferta_disciplina_id, (int) $dados['oferta_disciplina_id']]);
            sort($ids);
            $ofertas = OfertaDisciplina::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $nova = $ofertas->get((int) $dados['oferta_disciplina_id']);
            $this->validarTotal($nova, (int) $dados['horas_realizadas'],
                (int) $registros_aula->oferta_disciplina_id === (int) $nova->id ? $registros_aula->id : null);
            $registros_aula->update($dados);
            foreach ($ofertas as $oferta) $this->recalcular($oferta);
        });
        return redirect()->route('registros-aulas.index')->with('success', 'Aula atualizada com sucesso!');
    }

    public function destroy(RegistroAula $registros_aula)
    {
        DB::transaction(function () use ($registros_aula) {
            $oferta = OfertaDisciplina::whereKey($registros_aula->oferta_disciplina_id)->lockForUpdate()->firstOrFail();
            $registros_aula->delete();
            $this->recalcular($oferta);
        });
        return redirect()->route('registros-aulas.index')->with('success', 'Registro excluído e horas recalculadas.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'oferta_disciplina_id' => 'required|exists:oferta_disciplinas,id',
            'data_aula' => 'required|date|before_or_equal:today',
            'horas_realizadas' => 'required|integer|min:1|max:24',
            'tipo_atividade' => 'required|in:Teórica,Prática,Outra',
            'conteudo_ministrado' => 'required|string|max:5000',
        ]);
    }

    /** Não ultrapassa as horas previstas, preservando eventual saldo legado. */
    private function validarTotal(OfertaDisciplina $oferta, int $novasHoras, ?int $ignorarRegistro = null): void
    {
        $consulta = $oferta->registrosAulas();
        if ($ignorarRegistro !== null) $consulta->whereKeyNot($ignorarRegistro);
        $registradas = (int) $consulta->sum('horas_realizadas');
        $legado = (int) $oferta->horas_legadas;
        if ($legado + $registradas + $novasHoras > (int) $oferta->carga_horaria_prevista) {
            throw ValidationException::withMessages([
                'horas_realizadas' => 'A soma das aulas ultrapassaria a carga horária prevista. Confira também as horas cumpridas anteriormente informadas.',
            ]);
        }
    }

    /** Mantém saldo manual legado, somando aulas novas sem perder histórico anterior. */
    private function recalcular(OfertaDisciplina $oferta): void
    {
        $total = (int) $oferta->registrosAulas()->sum('horas_realizadas');
        // O saldo legado é armazenado separadamente para não se perder em edições.
        $oferta->carga_horaria_cumprida = (int) $oferta->horas_legadas + $total;
        $oferta->save();
    }

    private function ofertas()
    {
        return OfertaDisciplina::with(['disciplina', 'semestre', 'docente'])
            ->orderByDesc('semestre_id')->orderBy('disciplina_id')->get();
    }
}
