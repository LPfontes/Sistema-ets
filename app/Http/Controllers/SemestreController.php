<?php

// Namespace dos Controllers do Laravel.
namespace App\Http\Controllers;

// Importa o Model responsável pelos semestres.
use App\Models\Semestre;

// Permite receber os dados dos formulários.
use Illuminate\Http\Request;

// Permite validar valores únicos no banco.
use Illuminate\Validation\Rule;

/**
 * CONTROLLER: SEMESTRE
 *
 * Implementa o CRUD de Semestres (US3).
 *
 * Funcionalidades:
 * - Listar semestres
 * - Cadastrar semestre
 * - Visualizar semestre
 * - Editar semestre
 * - Excluir semestre
 */
class SemestreController extends Controller
{
    /**
     * INDEX - LISTAR SEMESTRES
     *
     * Busca os semestres cadastrados no banco.
     */
    public function index()
    {
        // Organiza os semestres pelo identificador,
        // começando pelo mais recente.
        $semestres = Semestre::orderBy(
            'identificador',
            'desc'
        )->get();

        // Envia os registros para a página de listagem.
        return view('semestres.index', compact('semestres'));
    }

    /**
     * CREATE - FORMULÁRIO DE CADASTRO
     */
    public function create()
    {
        // Abre o formulário de cadastro.
        return view('semestres.create');
    }

    /**
     * STORE - CADASTRAR SEMESTRE
     *
     * Recebe e valida os dados do formulário.
     */
    public function store(Request $request)
    {
        /*
         * REGRAS DE VALIDAÇÃO
         *
         * required:
         * O identificador é obrigatório.
         *
         * regex:
         * Aceita formatos como 2026.1 e 2026.2.
         *
         * unique:
         * Impede cadastrar o mesmo semestre
         * duas vezes no sistema.
         */
        $dados = $request->validate([
            'identificador' => [
                'required',
                'string',
                'regex:/^\d{4}\.[12]$/',
                'unique:semestres,identificador',
            ],
        ], [
            // Mensagens personalizadas de erro.
            'identificador.required' =>
                'Informe o identificador do semestre.',

            'identificador.regex' =>
                'Use o formato AAAA.1 ou AAAA.2, por exemplo 2026.2.',

            'identificador.unique' =>
                'Este semestre já está cadastrado.',
        ]);

        // Salva o novo semestre no MySQL.
        Semestre::create($dados);

        // Retorna à listagem com mensagem de sucesso.
        return redirect()
            ->route('semestres.index')
            ->with('success', 'Semestre cadastrado com sucesso!');
    }

    /**
     * VISUALIZAR DETALHES DE UM SEMESTRE
     *
     * Exibe as informações do semestre e todas
     * as disciplinas ofertadas nesse período.
     *
     * Exemplo:
     * Semestre: 2026.2
     *
     * Ofertas:
     * - Informática Básica - 60h
     * - Informática Complementar - 80h
     *
     * Cada oferta poderá apresentar o docente
     * responsável e a carga horária prevista.
     */
    public function show(Semestre $semestre)
    {
        /*
        * CARREGAR AS OFERTAS DO SEMESTRE
        *
        * ofertasDisciplinas é o relacionamento
        * definido no Model Semestre.
        *
        * ofertasDisciplinas.disciplina recupera
        * a disciplina de cada oferta.
        *
        * ofertasDisciplinas.docente recupera
        * o docente responsável pela oferta.
        */
        $semestre->load([
            'ofertasDisciplinas.disciplina',
            'ofertasDisciplinas.docente',
        ]);

        /*
        * RETORNAR A VIEW
        *
        * Envia o semestre com suas ofertas
        * para a página de detalhes.
        */
        return view('semestres.show', compact('semestre'));
    }


    /**
     * EDIT - FORMULÁRIO DE EDIÇÃO
     */
    public function edit(Semestre $semestre)
    {
        // Envia o semestre selecionado ao formulário.
        return view('semestres.edit', compact('semestre'));
    }

    /**
     * UPDATE - ATUALIZAR SEMESTRE
     */
    public function update(Request $request, Semestre $semestre)
    {
        /*
         * Valida o identificador.
         *
         * ignore($semestre->id):
         * Ignora o próprio registro durante a edição,
         * permitindo salvar sem alterar o identificador.
         */
        $dados = $request->validate([
            'identificador' => [
                'required',
                'string',
                'regex:/^\d{4}\.[12]$/',

                Rule::unique('semestres', 'identificador')
                    ->ignore($semestre->id),
            ],
        ], [
            'identificador.required' =>
                'Informe o identificador do semestre.',

            'identificador.regex' =>
                'Use o formato AAAA.1 ou AAAA.2, por exemplo 2026.2.',

            'identificador.unique' =>
                'Este semestre já está cadastrado.',
        ]);

        // Atualiza o semestre existente.
        $semestre->update($dados);

        // Retorna à listagem.
        return redirect()
            ->route('semestres.index')
            ->with('success', 'Semestre atualizado com sucesso!');
    }


/**
 * GERAR PERÍODOS LETIVOS AUTOMATICAMENTE
 *
 * Recebe um período inicial e um período final.
 *
 * Exemplo:
 * Início: 2026.2
 * Fim: 2028.1
 *
 * Resultado:
 * 2026.2, 2027.1, 2027.2, 2028.1
 *
 * Não duplica períodos já cadastrados.
 */
public function gerarPeriodos(Request $request)
{
    // Valida os dois períodos informados.
    $dados = $request->validate([
        'periodo_inicial' => [
            'required',
            'regex:/^\d{4}\.[12]$/',
        ],
        'periodo_final' => [
            'required',
            'regex:/^\d{4}\.[12]$/',
        ],
    ]);

    // Separa o ano e o semestre do período inicial.
    [$anoInicial, $semestreInicial] =
        array_map('intval', explode('.', $dados['periodo_inicial']));

    // Separa o ano e o semestre do período final.
    [$anoFinal, $semestreFinal] =
        array_map('intval', explode('.', $dados['periodo_final']));

    // Converte os períodos em números sequenciais
    // para facilitar a comparação cronológica.
    $inicio = $anoInicial * 2 + $semestreInicial;
    $fim = $anoFinal * 2 + $semestreFinal;

    // Impede intervalos invertidos.
    if ($fim < $inicio) {
        return back()->withErrors([
            'periodo_final' =>
                'O período final não pode ser anterior ao inicial.',
        ])->withInput();
    }

    // Limita o intervalo a 40 semestres (20 anos).
    // Evita gerar uma quantidade excessiva de registros.
    if (($fim - $inicio) > 39) {
        return back()->withErrors([
            'periodo_final' =>
                'O intervalo máximo permitido é de 40 semestres.',
        ])->withInput();
    }

    // Conta quantos períodos foram cadastrados.
    $criados = 0;

    // Percorre todos os períodos do intervalo.
    for ($periodo = $inicio; $periodo <= $fim; $periodo++) {

        // Recupera o ano correspondente.
        $ano = intdiv($periodo - 1, 2);

        // Recupera o semestre: 1 ou 2.
        $semestre = (($periodo - 1) % 2) + 1;

        // Monta o identificador no formato AAAA.S.
        $identificador = $ano . '.' . $semestre;

        // Cria somente se o semestre ainda não existir.
        $registro = Semestre::firstOrCreate([
            'identificador' => $identificador,
        ]);

        // Conta apenas os registros realmente novos.
        if ($registro->wasRecentlyCreated) {
            $criados++;
        }
    }

    // Retorna à listagem com uma mensagem de confirmação.
    return redirect()
        ->route('semestres.index')
        ->with(
            'success',
            "Geração concluída! {$criados} período(s) novo(s) cadastrado(s)."
        );
}


    /**
     * DESTROY - EXCLUIR SEMESTRE
     */
    public function destroy(Semestre $semestre)
    {
        /*
         * Exclui o semestre selecionado.
         *
         * Quando implementarmos as ofertas,
         * adicionaremos uma proteção para impedir
         * excluir semestres com disciplinas ofertadas.
         */
        $semestre->delete();

        // Retorna à listagem.
        return redirect()
            ->route('semestres.index')
            ->with('success', 'Semestre excluído com sucesso!');
    }
}
