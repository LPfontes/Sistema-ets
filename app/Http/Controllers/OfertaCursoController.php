<?php

namespace App\Http\Controllers;
// Serviço responsável pela geração automática de períodos letivos.
use App\Services\GeradorPeriodosLetivos;

// Permite executar operações no banco em uma transação.
use Illuminate\Support\Facades\DB;

// Permite retornar erros de validação ao formulário.
use Illuminate\Validation\ValidationException;

/*
 * MODEL OFERTA DE CURSO
 *
 * Permite cadastrar, consultar, atualizar
 * e excluir ofertas de cursos.
 */
use App\Models\OfertaCurso;

/*
 * MODEL CURSO
 *
 * Permite consultar os cursos cadastrados
 * para selecionar qual será ofertado.
 */
use App\Models\Curso;

/*
 * MODEL SEMESTRE
 *
 * Permite consultar os períodos letivos
 * cadastrados, como 2026.2 e 2027.1.
 */
use App\Models\Semestre;


use Illuminate\Http\Request;

class OfertaCursoController extends Controller
{
    
    
/**
 * LISTAR OFERTAS DE CURSOS
 *
 * Recupera as ofertas cadastradas,
 * incluindo o curso, o período inicial
 * e o período final.
 */
public function index()
{
    /*
     * with() carrega os relacionamentos:
     *
     * curso: informações do curso.
     * semestre: período letivo inicial.
     * semestreFinal: período letivo final.
     *
     * latest() ordena pelas ofertas
     * cadastradas mais recentemente.
     */
    $ofertas = OfertaCurso::with([
        'curso',
        'semestre',
        'semestreFinal',
    ])
    ->latest()
    ->get();

    // Envia as ofertas para a página de listagem.
    return view(
        'ofertas_cursos.index',
        compact('ofertas')
    );
}



    
    /**
     * EXIBIR FORMULÁRIO DE CADASTRO DE OFERTA DE CURSO
     *
     * Este método prepara os dados necessários
     * para cadastrar uma nova oferta.
     *
     * O formulário permitirá selecionar:
     * - Curso;
     * - Semestre inicial;
     * - Quantidade de turmas;
     * - Situação da oferta;
     * - Observações.
     */
    public function create()
    {
        /*
        * ETAPA 1 - BUSCAR OS CURSOS
        *
        * Consulta a tabela cursos.
        *
        * orderBy('nome') organiza os cursos
        * em ordem alfabética.
        *
        * get() retorna todos os registros.
        */
        $cursos = Curso::orderBy('nome')->get();

        /*
        * ETAPA 2 - BUSCAR OS SEMESTRES
        *
        * Consulta os períodos letivos cadastrados.
        *
        * Exemplo:
        * - 2026.2
        * - 2027.1
        *
        * orderByDesc() apresenta os períodos
        * mais recentes primeiro.
        */
        $semestres = Semestre::orderByDesc('identificador')
            ->get();

        /*
        * ETAPA 3 - ABRIR O FORMULÁRIO
        *
        * Retorna a View ofertas_cursos.create.
        *
        * compact() envia as variáveis
        * $cursos e $semestres para a página.
        */
        return view(
            'ofertas_cursos.create',
            compact('cursos', 'semestres')
        );
    }


    
    
/**
 * CADASTRAR OFERTA DE CURSO
 *
 * Cadastra uma oferta e gera automaticamente
 * os períodos letivos intermediários.
 */
public function store(
    Request $request,
    GeradorPeriodosLetivos $gerador
) {
    /*
     * ETAPA 1 — VALIDAR OS DADOS
     *
     * semestre_id:
     * Período inicial já cadastrado.
     *
     * periodo_final:
     * Identificador do período final informado
     * pelo professor, por exemplo 2028.1.
     */
    $dados = $request->validate([
        'curso_id' => 'required|exists:cursos,id',

        'semestre_id' => 'required|exists:semestres,id',

        'periodo_final' => [
            'required',
            'regex:/^\d{4}\.[12]$/',
        ],

        'quantidade_turmas' => 'required|integer|min:1',

        'situacao' => 'required|in:Planejada,Em andamento,Concluída',

        'observacao' => 'nullable|string|max:2000',
    ]);

    /*
     * ETAPA 2 — IDENTIFICAR O PERÍODO INICIAL
     *
     * Busca o semestre selecionado no formulário.
     */
    $semestreInicial = Semestre::findOrFail(
        $dados['semestre_id']
    );

    /*
     * ETAPA 3 — VALIDAR A ORDEM DOS PERÍODOS
     *
     * Exemplo:
     * Início: 2026.2
     * Fim: 2028.1
     *
     * O período final não pode ser anterior.
     */
    $inicio = $semestreInicial->identificador;
    $fim = $dados['periodo_final'];

    // Converte AAAA.S em um número comparável.
    $numeroInicio =
        (int) substr($inicio, 0, 4) * 2
        + (int) substr($inicio, 5, 1);

    $numeroFim =
        (int) substr($fim, 0, 4) * 2
        + (int) substr($fim, 5, 1);

    if ($numeroFim < $numeroInicio) {
        throw ValidationException::withMessages([
            'periodo_final' =>
                'O período final não pode ser anterior ao inicial.',
        ]);
    }

    // Aplica o mesmo limite de 40 semestres do serviço.
    if (($numeroFim - $numeroInicio) > 39) {
        throw ValidationException::withMessages([
            'periodo_final' =>
                'O intervalo máximo permitido é de 40 semestres.',
        ]);
    }

    /*
     * ETAPA 4 — GERAR PERÍODOS E SALVAR A OFERTA
     *
     * DB::transaction garante que as operações
     * sejam concluídas juntas.
     *
     * Se ocorrer algum erro, as alterações
     * dessa transação serão desfeitas.
     */
    DB::transaction(function () use (
        $dados,
        $inicio,
        $fim,
        $gerador
    ) {
        // Cadastra os períodos ainda inexistentes.
        $gerador->gerar($inicio, $fim);

        // Recupera o período final cadastrado.
        $semestreFinal = Semestre::where(
            'identificador',
            $fim
        )->firstOrFail();

        // Associa o ID do período final à oferta.
        $dados['semestre_fim_id'] = $semestreFinal->id;

        // Remove o campo auxiliar, que não pertence
        // à tabela oferta_cursos.
        unset($dados['periodo_final']);

        // Cadastra a oferta de curso.
        OfertaCurso::create($dados);
    });

    /*
     * ETAPA 5 — REDIRECIONAR
     */
    return redirect()
        ->route('ofertas-cursos.index')
        ->with(
            'success',
            'Oferta cadastrada e períodos letivos gerados com sucesso!'
        );
}



    
    
/**
 * VISUALIZAR UMA OFERTA DE CURSO
 *
 * Apresenta as informações da oferta:
 * - Curso;
 * - Semestre inicial;
 * - Semestre final;
 * - Quantidade de turmas;
 * - Situação;
 * - Observação.
 */
public function show(string $id)
{
    /*
     * ETAPA 1 — CONSULTAR A OFERTA
     *
     * with() carrega os relacionamentos:
     *
     * curso: informações do curso.
     * semestre: período inicial.
     * semestreFinal: período final.
     *
     * findOrFail() retorna erro 404
     * caso a oferta não exista.
     */
    $oferta = OfertaCurso::with([
        'curso',
        'semestre',
        'semestreFinal',
    ])->findOrFail($id);

    /*
     * ETAPA 2 — EXIBIR A PÁGINA
     *
     * Envia a oferta para a View.
     */
    return view(
        'ofertas_cursos.show',
        compact('oferta')
    );
}



    
    /**
     * EDITAR UMA OFERTA DE CURSO
     *
     * Busca a oferta selecionada e carrega
     * os cursos e semestres cadastrados.
     *
     * Esses dados serão utilizados para
     * preencher o formulário de edição.
     *
     * @param string $id Identificador da oferta
     */
    public function edit(string $id)
    {
        /*
        * ETAPA 1 — BUSCAR A OFERTA
        *
        * findOrFail() consulta a oferta pelo ID.
        *
        * Se a oferta não existir, o Laravel
        * retorna automaticamente um erro 404.
        */
       // Busca a oferta e carrega também o período final.
// Esse relacionamento preenche o campo no formulário.
$oferta = OfertaCurso::with('semestreFinal')->findOrFail($id);

        /*
        * ETAPA 2 — BUSCAR OS CURSOS
        *
        * Carrega os cursos cadastrados
        * em ordem alfabética.
        *
        * O usuário poderá selecionar um curso
        * no formulário de edição.
        */
        $cursos = Curso::orderBy('nome')->get();

        /*
        * ETAPA 3 — BUSCAR OS SEMESTRES
        *
        * Carrega os períodos letivos cadastrados.
        *
        * Exemplo:
        * 2026.2
        * 2027.1
        */
        $semestres = Semestre::orderByDesc('identificador')
            ->get();

        /*
        * ETAPA 4 — ABRIR O FORMULÁRIO DE EDIÇÃO
        *
        * Envia três variáveis para a View:
        *
        * $oferta    -> oferta que será editada
        * $cursos    -> cursos disponíveis
        * $semestres -> períodos letivos disponíveis
        */
        return view(
            'ofertas_cursos.edit',
            compact('oferta', 'cursos', 'semestres')
        );
    }


    
    /**
     * ATUALIZAR UMA OFERTA DE CURSO
     *
     * Recebe os dados enviados pelo formulário
     * de edição, valida as informações e
     * atualiza a oferta no banco de dados.
     *
     * @param Request $request Dados do formulário
     * @param string $id Identificador da oferta
     */
    public function update(Request $request, string $id, GeradorPeriodosLetivos $gerador)
    {
        // A oferta existente é atualizada, sem criar outra.
        $oferta = OfertaCurso::findOrFail($id);
        $dados = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'semestre_id' => 'required|exists:semestres,id',
            'periodo_final' => ['required', 'regex:/^\d{4}\.[12]$/'],
            'quantidade_turmas' => 'required|integer|min:1',
            'situacao' => 'required|in:Planejada,Em andamento,Concluída',
            'observacao' => 'nullable|string|max:2000',
        ]);
        $inicio = Semestre::findOrFail($dados['semestre_id'])->identificador;
        $fim = $dados['periodo_final'];
        $numero = static function (string $periodo): int {
            [$ano, $semestre] = array_map('intval', explode('.', $periodo));
            return $ano * 2 + $semestre;
        };
        if ($numero($fim) < $numero($inicio) || $numero($fim) - $numero($inicio) > 39) {
            throw ValidationException::withMessages([
                'periodo_final' => 'O semestre final deve ser igual ou posterior ao inicial (máximo de 40 períodos).',
            ]);
        }
        DB::transaction(function () use ($dados, $inicio, $fim, $gerador, $oferta) {
            // O serviço utiliza firstOrCreate, evitando duplicações.
            $gerador->gerar($inicio, $fim);
            $dados['semestre_fim_id'] = Semestre::where('identificador', $fim)->firstOrFail()->id;
            unset($dados['periodo_final']);
            $oferta->update($dados);
        });
        return redirect()->route('ofertas-cursos.show', $oferta->id)
            ->with('success', 'Oferta atualizada e períodos letivos verificados com sucesso!');
    }

    /** Exclui a oferta apenas quando não há vínculos que impeçam a exclusão. */
public function destroy(string $id)
{
    /*
     * ETAPA 1 — LOCALIZAR A OFERTA
     *
     * findOrFail() busca a oferta pelo ID.
     *
     * Se não encontrar o registro,
     * o Laravel retorna o erro HTTP 404.
     */
    $oferta = OfertaCurso::findOrFail($id);

    /*
     * ETAPA 2 — EXCLUIR A OFERTA
     *
     * delete() remove o registro da tabela
     * oferta_cursos.
     *
     * ATENÇÃO:
     * A exclusão só será permitida se
     * não houver restrições de integridade
     * no banco de dados.
     */
    $oferta->delete();

    /*
     * ETAPA 3 — REDIRECIONAR
     *
     * Após excluir, retorna à listagem
     * e apresenta uma mensagem de sucesso.
     */
    return redirect()
        ->route('ofertas-cursos.index')
        ->with(
            'success',
            'Oferta de curso excluída com sucesso!'
        );
}

}
