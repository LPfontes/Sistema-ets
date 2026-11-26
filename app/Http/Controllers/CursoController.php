<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    // Listar cursos
    public function index()
    {
        $cursos = Curso::orderBy('nome')->get();

        return view('cursos.index', compact('cursos'));
    }

    // Formulário de cadastro
    public function create()
    {
        // Renderiza o formulário de cadastro sem
        // modificar as informações do banco de dados.
        return view('cursos.create');
    }

    // Salvar novo curso
    public function store(Request $request)
    {
        $dados = $this->validarCurso($request);
        // O arquivo enviado não deve ser passado diretamente ao model.
        unset($dados['projeto_pedagogico']);

        // Salva o curso antes de cadastrar os módulos, garantindo que ele tenha um ID.
        // Guarda o PPC em storage/app/public/ppcs somente quando enviado.
        if ($request->hasFile('projeto_pedagogico')) {
            $dados['projeto_pedagogico'] = $request->file('projeto_pedagogico')->store('ppcs', 'public');
        }
        $curso = Curso::create($dados);

        // O segundo botão direciona para a página separada de módulos.
        // A intenção é validada para evitar redirecionamentos arbitrários.
        if ($request->input('acao') === 'salvar_e_adicionar_modulo') {
            return redirect()->route('modulos.create', ['curso_id' => $curso->id])
                ->with('success', 'Curso salvo! Agora cadastre o módulo.');
        }

        return redirect()->route('cursos.index')
            ->with('success', 'Curso cadastrado com sucesso!');
    }

    // Visualizar curso
    public function show(Curso $curso)
    {
        return view('cursos.show', compact('curso'));
    }


/**
 * PÁGINA DE SELEÇÃO DO CURSO PARA O EXTRATO
 *
 * Busca todos os cursos cadastrados e os envia
 * para a página onde o professor escolherá
 * qual curso deseja analisar.
 *
 * Este método apenas consulta o banco de dados.
 */
public function selecionarCursoExtrato()
{
    // Busca os cursos em ordem alfabética.
    $cursos = Curso::orderBy('nome')->get();

    // Abre a página de seleção do curso.
    return view('cursos.selecionar-extrato', compact('cursos'));
}



/**
 * ANÁLISE DE CARGA HORÁRIA POR CURSO
 *
 * Organiza os módulos de um curso por semestre curricular
 * e calcula as cargas horárias correspondentes.
 *
 * O campo periodo_oferta indica o semestre curricular
 * ao qual cada módulo pertence.
 *
 * Este método apenas consulta informações.
 * Nenhum dado é alterado no banco.
 */
public function analisarCargaHoraria(Curso $curso)
{
    // Busca os módulos do curso e suas disciplinas.
    // O relacionamento "modulos" deve existir no Model Curso.
    $curso->load('modulos.disciplinas');

    // Agrupa os módulos pelo período curricular.
    // Exemplo: período 1 pode conter um ou vários módulos.
    $modulosPorPeriodo = $curso->modulos
        ->groupBy('periodo_oferta')
        ->sortKeys();

    // Percorre cada período e calcula seus totais.
    $analise = $modulosPorPeriodo->map(function ($modulos, $periodo) {

        // Soma a carga horária oficial de todos os módulos.
        $cargaHorariaOficial = $modulos->sum('carga_horaria');

        // Reúne todas as disciplinas dos módulos do período.
        $disciplinas = $modulos->flatMap->disciplinas;

        // Soma a carga horária das disciplinas cadastradas.
        $cargaHorariaDisciplinas = $disciplinas->sum('carga_horaria');

        // Calcula a diferença entre a CH oficial e a cadastrada.
        $diferenca = $cargaHorariaOficial - $cargaHorariaDisciplinas;

        // Retorna os dados que serão exibidos no relatório.
        return [
            'periodo' => $periodo,
            'modulos' => $modulos,
            'quantidade_modulos' => $modulos->count(),
            'quantidade_disciplinas' => $disciplinas->count(),
            'carga_horaria_oficial' => $cargaHorariaOficial,
            'carga_horaria_disciplinas' => $cargaHorariaDisciplinas,
            'diferenca' => $diferenca,
        ];
    });

    // Envia o curso e os cálculos para a página de análise.
    return view('cursos.analise-carga-horaria', compact('curso', 'analise'));
}


    // Formulário de edição
    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    // Atualizar curso
    public function update(Request $request, Curso $curso)
    {
        $dados = $this->validarCurso($request);
        // O arquivo enviado não deve ser passado diretamente ao model.
        unset($dados['projeto_pedagogico']);

        // Mantém o PPC anterior quando nenhum arquivo novo é enviado.
        if ($request->hasFile('projeto_pedagogico')) {
            $novoPpc = $request->file('projeto_pedagogico')->store('ppcs', 'public');
            $anterior = $curso->projeto_pedagogico;
            $dados['projeto_pedagogico'] = $novoPpc;
            $curso->update($dados);
            // Apaga o arquivo antigo somente após salvar o novo caminho.
            if ($anterior && !filter_var($anterior, FILTER_VALIDATE_URL) && Storage::disk('public')->exists($anterior)) {
                Storage::disk('public')->delete($anterior);
            }
        } else {
            $curso->update($dados);
        }

        return redirect()->route('cursos.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    // Excluir curso
    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('cursos.index')
            ->with('success', 'Curso excluído com sucesso!');
    }

    // Validar os campos do formulário
    private function validarCurso(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'codigo_estrutura' => 'required|string|max:30',
            'turno' => 'required|in:Matutino,Vespertino,Noturno,Integral',
            'periodo_vigencia' => [
                'required',
                'regex:/^\d{4}\.[12]$/',
            ],
            'ativo' => 'required|boolean',
            'prazo_minimo' => 'nullable|integer|min:0',
            'prazo_maximo' => 'nullable|integer|min:0|gte:prazo_minimo',
            'carga_horaria_total' => 'required|integer|min:1',
            'quantidade_modulos' => 'required|integer|min:1',
            'resolucao' => 'nullable|string|max:255',
            'projeto_pedagogico' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'tipo_curso' => 'required|in:Presencial,EAD,Hibrido',
        ]);
    }
}
