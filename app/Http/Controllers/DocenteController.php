<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocenteController extends Controller
{
    // READ : afficher tous les enseignants
    public function index()
    {
        $docentes = Docente::orderBy('nome')->get();

        return view('docentes.index', compact('docentes'));
    }

    // CREATE : afficher le formulaire
    public function create()
    {
        return view('docentes.create');
    }

    // CREATE : enregistrer un enseignant
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'colegiado' => 'required|string|max:255',
            'siape' => 'required|string|max:20|unique:docentes,siape',
        ]);

        Docente::create($dados);

        return redirect()->route('docentes.index')
            ->with('success', 'Docente cadastrado com sucesso!');
    }

    // READ : afficher un enseignant
    public function show(Docente $docente)
    {
        // Consulta as ofertas realmente atribuídas ao docente.
        // Não usa apenas o professor indicado no cadastro da disciplina.
        $docente->load(['ofertasDisciplinas' => function ($consulta) {
            $consulta->with(['disciplina', 'semestre', 'ofertaCurso.curso'])
                ->orderBy('semestre_id')
                ->orderBy('id');
        }]);

        $ofertas = $docente->ofertasDisciplinas;

        // Utiliza os totais consolidados da oferta (incluindo aulas registradas).
        $totalPrevisto = $ofertas->sum('carga_horaria_prevista');
        $totalCumprido = $ofertas->sum('carga_horaria_cumprida');
        $totalRestante = max(0, $totalPrevisto - $totalCumprido);

        return view('docentes.show', compact(
            'docente', 'ofertas', 'totalPrevisto', 'totalCumprido', 'totalRestante'
        ));
    }

    // UPDATE : afficher le formulaire de modification
    public function edit(Docente $docente)
    {
        return view('docentes.edit', compact('docente'));
    }

    // UPDATE : enregistrer les modifications
    public function update(Request $request, Docente $docente)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'colegiado' => 'required|string|max:255',
            'siape' => [
                'required',
                'string',
                'max:20',
                Rule::unique('docentes', 'siape')->ignore($docente->id),
            ],
        ]);

        $docente->update($dados);

        return redirect()->route('docentes.index')
            ->with('success', 'Docente atualizado com sucesso!');
    }

    // DELETE : excluir um enseignant
    public function destroy(Docente $docente)
    {
        $docente->delete();

        return redirect()->route('docentes.index')
            ->with('success', 'Docente excluído com sucesso!');
    }
}
