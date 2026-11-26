<?php

// Define o namespace dos Controllers.
namespace App\Http\Controllers;

// Importa o Model de módulos.
use App\Models\Modulo;
// Permite criar regras de validação personalizadas.
use Illuminate\Validation\Rule;

// Importa o Model de cursos.
use App\Models\Curso;

// Importa a classe que representa uma requisição HTTP.
use Illuminate\Http\Request;

/**
 * CONTROLLER: MODULO
 *
 * Responsável pelo CRUD de Módulos
 * do Sistema ETS/UFPB.
 */
class ModuloController extends Controller
{
    /**
     * INDEX - LISTAR MÓDULOS
     *
     * Consulta todos os módulos cadastrados
     * e envia os dados para a página de listagem.
     */
    public function index()
    {
        /*
         * with('curso'):
         * Carrega também o curso de cada módulo.
         *
         * orderBy():
         * Organiza os módulos por período de oferta.
         *
         * get():
         * Executa a consulta ao banco de dados.
         */
        $modulos = Modulo::with('curso')
            ->orderBy('periodo_oferta')
            ->get();

        // Envia os módulos para a página index.
        return view('modulos.index', compact('modulos'));
    }

    /**
     * CREATE - FORMULÁRIO DE CADASTRO
     *
     * Busca os cursos existentes para preencher
     * o campo de seleção do formulário.
     */
    public function create()
    {
        // Consulta os cursos em ordem alfabética.
        $cursos = Curso::orderBy('nome')->get();

        // Envia a lista de cursos ao formulário.
        // Pré-seleciona o curso ao chegar pelo botão do cadastro de cursos.
        $cursoSelecionadoId = request()->query('curso_id');
        $cursoSelecionado = $cursoSelecionadoId
            ? Curso::find($cursoSelecionadoId)
            : null;

        return view('modulos.create', compact('cursos', 'cursoSelecionado'));
    }

    /**
     * STORE - SALVAR NOVO MÓDULO
     *
     * Recebe os dados enviados pelo formulário.
     */
    public function store(Request $request)
    {
        
/*
 * VALIDAÇÃO DO CADASTRO DE MÓDULOS
 *
 * Verifica os campos obrigatórios e impede
 * cadastrar duas vezes o mesmo módulo
 * dentro do mesmo curso.
 */
$dados = $request->validate([

    // Verifica se o curso selecionado existe.
    'curso_id' => [
        'required',
        'exists:cursos,id',
    ],

        /*
        * NOME DO MÓDULO
        *
        * Rule::unique('modulos', 'nome'):
        * Verifica se o nome já existe na tabela.
        *
        * where('curso_id', ...):
        * Limita essa verificação ao curso selecionado.
        *
        * Assim, dois cursos diferentes podem possuir
        * um módulo chamado UNIDADE CURRICULAR I.
        */
        'nome' => [
            'required',
            'string',
            'max:255',

            Rule::unique('modulos', 'nome')
                ->where('curso_id', $request->curso_id),
        ],

        // A carga horária precisa ser positiva.
        'carga_horaria' => [
            'required',
            'integer',
            'min:1',
        ],

        // O período de oferta precisa ser positivo.
        'periodo_oferta' => [
            'required',
            'integer',
            'min:1',
        ],

    ], [

        /*
        * Mensagem personalizada quando o nome
        * já estiver cadastrado no mesmo curso.
        */
        'nome.unique' =>
            'Este módulo já está cadastrado para o curso selecionado.',

    ]);

        

        // Cadastra o módulo no banco MySQL.
        Modulo::create($dados);

        // Quando o módulo veio do fluxo de cadastro do curso,
        // retorna à edição desse mesmo curso para continuar o trabalho.
        if ($request->input('retornar_ao_curso') === '1') {
            return redirect()->route('cursos.edit', ['curso' => $dados['curso_id']])
                ->with('success', 'Módulo cadastrado! Você pode adicionar outro módulo.');
        }

        // Retorna à listagem com uma mensagem.
        return redirect()
            ->route('modulos.index')
            ->with('success', 'Módulo cadastrado com sucesso!');
    }

    /**
     * SHOW - VISUALIZAR MÓDULO
     *
     * Recebe o módulo selecionado pela rota.
     */
    public function show(Modulo $modulo)
    {
        // Carrega o curso e todas as disciplinas vinculadas ao módulo.
        // A relação disciplinas() já existe no Model Modulo.
        $modulo->load(['curso', 'disciplinas']);

        // Ordena as disciplinas por nome para facilitar a consulta.
        $disciplinas = $modulo->disciplinas->sortBy('nome');

        // Soma as cargas horárias cadastradas das disciplinas do módulo.
        $totalHorasDisciplinas = $disciplinas->sum('carga_horaria');

        return view('modulos.show', compact('modulo', 'disciplinas', 'totalHorasDisciplinas'));
    }

    /**
     * EDIT - FORMULÁRIO DE EDIÇÃO
     *
     * Carrega o módulo e os cursos disponíveis.
     */
    public function edit(Modulo $modulo)
    {
        // Busca os cursos para o campo de seleção.
        $cursos = Curso::orderBy('nome')->get();

        // Envia os dados para a página de edição.
        return view('modulos.edit', compact('modulo', 'cursos'));
    }

    /**
     * UPDATE - ATUALIZAR MÓDULO
     *
     * Recebe os novos dados do formulário.
     */
    
    /**
     * UPDATE - ATUALIZAR MÓDULO
     *
     * Atualiza os dados de um módulo existente,
     * impedindo nomes duplicados dentro do mesmo curso.
     */
    public function update(Request $request, Modulo $modulo)
    {
        /*
        * Valida os dados enviados pelo formulário.
        */
        $dados = $request->validate([

            // O curso selecionado deve existir.
            'curso_id' => [
                'required',
                'exists:cursos,id',
            ],

            /*
            * O nome deve ser único dentro do curso.
            *
            * ignore($modulo->id):
            * Ignora o próprio módulo durante a edição.
            *
            * Assim, podemos salvar um módulo sem
            * precisar alterar seu nome.
            *
            * where('curso_id', ...):
            * Verifica duplicações apenas no curso
            * selecionado pelo usuário.
            */
            'nome' => [
                'required',
                'string',
                'max:255',

                Rule::unique('modulos', 'nome')
                    ->ignore($modulo->id)
                    ->where('curso_id', $request->curso_id),
            ],

            // A carga horária deve ser maior que zero.
            'carga_horaria' => [
                'required',
                'integer',
                'min:1',
            ],

            /*
            * Permite módulos diferentes no mesmo
            * período de oferta.
            */
            'periodo_oferta' => [
                'required',
                'integer',
                'min:1',
            ],

        ], [

            // Mensagem para nomes duplicados.
            'nome.unique' =>
                'Já existe um módulo com este nome no curso selecionado.',

        ]);

        // Atualiza o registro existente no MySQL.
        $modulo->update($dados);

        // Retorna à listagem após a atualização.
        return redirect()
            ->route('modulos.index')
            ->with('success', 'Módulo atualizado com sucesso!');
    }


    /**
     * DESTROY - EXCLUIR MÓDULO
     *
     * Remove um módulo do banco de dados.
     */
    public function destroy(Modulo $modulo)
    {
        /*
         * Exclui o módulo selecionado.
         *
         * Antes de disponibilizar essa operação
         * aos usuários, verificaremos os vínculos
         * com disciplinas para evitar perda de dados.
         */
        $modulo->delete();

        // Retorna à listagem após a exclusão.
        return redirect()
            ->route('modulos.index')
            ->with('success', 'Módulo excluído com sucesso!');
    }
}
