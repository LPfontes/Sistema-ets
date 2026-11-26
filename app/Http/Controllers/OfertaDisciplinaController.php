<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


 // Importa o Model responsável pelas ofertas de disciplinas.
 use App\Models\OfertaDisciplina;

 
// Importa o Model de Disciplinas.
// Será utilizado para listar as disciplinas disponíveis.
use App\Models\Disciplina;

// Importa o Model de Semestres.
// Será utilizado para selecionar o período letivo.
use App\Models\Semestre;

// Importa o Model de Docentes.
// Será utilizado para selecionar o professor responsável.
use App\Models\Docente;
use App\Models\OfertaCurso;
use Illuminate\Validation\ValidationException;







class OfertaDisciplinaController extends Controller
{
    
    /**
     * LISTAR OFERTAS DE DISCIPLINAS
     *
     * Recupera todas as ofertas cadastradas e
     * suas informações relacionadas:
     *
     * - Disciplina
     * - Semestre
     * - Docente
     *
     * Depois envia os dados para a página de listagem.
     */
    public function index()
    {
        // Consulta as ofertas cadastradas.
        //
        // with() carrega os relacionamentos para evitar
        // consultas adicionais ao acessar cada registro.
        //
        // latest() organiza da oferta mais recentemente
        // cadastrada para a mais antiga.
        $ofertas = OfertaDisciplina::with([
            'disciplina',
            'semestre',
            'docente'
        ])
        ->latest()
        ->get();

        // Envia a variável $ofertas para a View.
        return view('ofertas_disciplinas.index', compact('ofertas'));
    }


        
    /**
     * EXIBIR FORMULÁRIO DE CADASTRO DE OFERTAS
     *
     * Este método prepara as informações necessárias
     * para cadastrar uma nova oferta de disciplina.
     *
     * Ele recupera:
     * - Disciplinas cadastradas;
     * - Semestres cadastrados;
     * - Docentes cadastrados.
     *
     * Depois envia essas informações para a View.
     */
    public function create()
    {
        /*
        * DISCIPLINAS
        *
        * Recupera todas as disciplinas cadastradas.
        *
        * with('curso') carrega também o curso
        * relacionado a cada disciplina.
        *
        * orderBy('nome') organiza alfabeticamente.
        */
        $disciplinas = Disciplina::with('curso')
            ->orderBy('nome')
            ->get();

        /*
        * SEMESTRES
        *
        * Recupera os semestres cadastrados.
        *
        * Exemplo: 2026.2, 2027.1, 2027.2.
        *
        * orderByDesc apresenta os períodos mais
        * recentes primeiro.
        */
        $semestres = Semestre::orderByDesc('identificador')
            ->get();

        /*
        * DOCENTES
        *
        * Recupera os professores cadastrados,
        * organizados alfabeticamente.
        */
        $docentes = Docente::orderBy('nome')->get();

        // Ofertas disponíveis para associar a disciplina a uma turma/coorte.
        $ofertasCursos = OfertaCurso::with(['curso', 'semestre', 'semestreFinal'])->latest()->get();

        /*
        * RETORNAR A VIEW
        *
        * compact() envia as três variáveis
        * para o formulário de cadastro.
        */
        return view('ofertas_disciplinas.create', compact(
            'disciplinas',
            'semestres',
            'docentes',
            'ofertasCursos'
        ));
    }


    
/**
 * SALVAR UMA NOVA OFERTA DE DISCIPLINA
 *
 * Recebe os dados do formulário, valida as informações
 * e cadastra a oferta no banco de dados.
 *
 * Campos:
 * - disciplina_id: disciplina ofertada;
 * - semestre_id: período letivo da oferta;
 * - docente_id: professor responsável (opcional);
 * - carga_horaria_prevista: horas previstas para a oferta.
 */
    public function store(Request $request)
    {
        /*
        * ETAPA 1 - VALIDAR OS DADOS
        *
        * required: campo obrigatório.
        * exists: verifica se o registro existe no banco.
        * nullable: permite que o campo fique vazio.
        * integer: exige um número inteiro.
        * min:1: exige um valor de pelo menos 1.
        */
        $dados = $request->validate([

            'disciplina_id' => [
                'required',
                'exists:disciplinas,id',
            ],

            'semestre_id' => [
                'required',
                'exists:semestres,id',
            ],

            'docente_id' => [
                'nullable',
                'exists:docentes,id',
            ],

            'carga_horaria_prevista' => [
                'required',
                'integer',
                'min:1',
            ],
            'carga_horaria_cumprida' => 'required|integer|min:0',
            'oferta_curso_id' => 'nullable|exists:oferta_cursos,id',

        ], [

            /*
            * ETAPA 2 - MENSAGENS PERSONALIZADAS
            *
            * Estas mensagens serão exibidas quando
            * algum campo não passar pela validação.
            */
            'disciplina_id.required' => 'Selecione uma disciplina.',
            'disciplina_id.exists' => 'A disciplina selecionada não existe.',

            'semestre_id.required' => 'Selecione um semestre.',
            'semestre_id.exists' => 'O semestre selecionado não existe.',

            'docente_id.exists' => 'O docente selecionado não existe.',

            'carga_horaria_prevista.required' => 'Informe a carga horária prevista.',
            'carga_horaria_prevista.integer' => 'A carga horária deve ser um número inteiro.',
            'carga_horaria_prevista.min' => 'A carga horária deve ser maior que zero.',

        ]);

        /*
        * ETAPA 3 - SALVAR A OFERTA
        *
        * O método create() insere um novo registro
        * na tabela oferta_disciplinas.
        *
        * Os campos permitidos já foram definidos
        * no $fillable do Model OfertaDisciplina.
        */
        $this->validarVinculo($dados);
        // Horas informadas no cadastro são o saldo inicial anterior aos registros de aulas.
        $dados['horas_legadas'] = $dados['carga_horaria_cumprida'];
        OfertaDisciplina::create($dados);

        /*
        * ETAPA 4 - REDIRECIONAR
        *
        * Após o cadastro, retorna à listagem
        * e envia uma mensagem de sucesso.
        */
        return redirect()
            ->route('ofertas-disciplinas.index')
            ->with('success', 'Oferta de disciplina cadastrada com sucesso!');
    }


    /**
     * Exibe os detalhes de uma oferta de disciplina.
     *
     * O nome $ofertas_disciplina corresponde ao
     * parâmetro da rota resource.
     */
    public function show(OfertaDisciplina $ofertas_disciplina)
    {
        // Carrega os dados relacionados à oferta.
        $ofertas_disciplina->load([
            'disciplina',
            'semestre',
            'docente',
        ]);

        // Envia a oferta para a página de detalhes.
        return view('ofertas_disciplinas.show', [
            'oferta' => $ofertas_disciplina,
        ]);
    }

   
    /**
     * EXIBIR FORMULÁRIO DE EDIÇÃO DE OFERTA
     *
     * Recupera a oferta selecionada e prepara
     * as listas de disciplinas, semestres e docentes.
     *
     * Assim, o formulário poderá apresentar os dados
     * atuais e permitir que o usuário os altere.
     */
    public function edit(OfertaDisciplina $ofertas_disciplina)
    {
        // Busca as disciplinas cadastradas,
        // incluindo o curso de cada disciplina.
        $disciplinas = Disciplina::with('curso')
            ->orderBy('nome')
            ->get();

        // Busca os semestres cadastrados,
        // apresentando os mais recentes primeiro.
        $semestres = Semestre::orderByDesc('identificador')
            ->get();

        // Busca os docentes em ordem alfabética.
        $docentes = Docente::orderBy('nome')->get();

        // Ofertas disponíveis para associar a disciplina a uma turma/coorte.
        $ofertasCursos = OfertaCurso::with(['curso', 'semestre', 'semestreFinal'])->latest()->get();

        // Envia a oferta e as listas para a View de edição.
        return view('ofertas_disciplinas.edit', [
            'oferta' => $ofertas_disciplina,
            'disciplinas' => $disciplinas,
            'semestres' => $semestres,
            'docentes' => $docentes,
            'ofertasCursos' => $ofertasCursos,
        ]);
    }


    
    /**
     * ATUALIZAR UMA OFERTA DE DISCIPLINA
     *
     * Recebe os dados enviados pelo formulário
     * de edição e atualiza a oferta existente.
     *
     * Permite modificar:
     * - Disciplina;
     * - Semestre;
     * - Docente responsável;
     * - Carga horária prevista.
     *
     * O Laravel identifica a oferta pelo parâmetro
     * $ofertas_disciplina, recebido pela rota.
     */
    public function update(
        Request $request,
        OfertaDisciplina $ofertas_disciplina
    ) {
        /*
        * ETAPA 1 - VALIDAR OS DADOS
        *
        * required: campo obrigatório.
        * exists: verifica se o registro existe.
        * nullable: permite deixar o campo vazio.
        * integer: exige número inteiro.
        * min:1: exige valor maior que zero.
        */
        $dados = $request->validate([

            'disciplina_id' => [
                'required',
                'exists:disciplinas,id',
            ],

            'semestre_id' => [
                'required',
                'exists:semestres,id',
            ],

            'docente_id' => [
                'nullable',
                'exists:docentes,id',
            ],

            'carga_horaria_prevista' => [
                'required',
                'integer',
                'min:1',
            ],
            'carga_horaria_cumprida' => 'required|integer|min:0',
            'oferta_curso_id' => 'nullable|exists:oferta_cursos,id',

        ], [

            /*
            * ETAPA 2 - MENSAGENS DE VALIDAÇÃO
            *
            * São apresentadas quando os dados
            * não atendem às regras estabelecidas.
            */
            'disciplina_id.required' => 'Selecione uma disciplina.',
            'disciplina_id.exists' => 'A disciplina selecionada não existe.',

            'semestre_id.required' => 'Selecione um semestre.',
            'semestre_id.exists' => 'O semestre selecionado não existe.',

            'docente_id.exists' => 'O docente selecionado não existe.',

            'carga_horaria_prevista.required' => 'Informe a carga horária prevista.',
            'carga_horaria_prevista.integer' => 'A carga horária deve ser um número inteiro.',
            'carga_horaria_prevista.min' => 'A carga horária deve ser maior que zero.',

        ]);

        /*
        * ETAPA 3 - ATUALIZAR A OFERTA
        *
        * update() altera o registro existente.
        *
        * Diferentemente de create(), não cria
        * uma nova oferta no banco de dados.
        */
        $this->validarVinculo($dados);
        // O valor manual representa somente o saldo anterior aos registros de aulas.
        // Nunca sobrescrevemos a soma dos registros de aulas já existentes.
        $horasAulas = (int) $ofertas_disciplina->registrosAulas()->sum('horas_realizadas');
        $horasManuais = (int) $dados['carga_horaria_cumprida'];
        if ($horasManuais + $horasAulas > (int) $dados['carga_horaria_prevista']) {
            throw ValidationException::withMessages([
                'carga_horaria_cumprida' => 'Horas anteriores mais aulas registradas ultrapassam as horas previstas.',
            ]);
        }
        $dados['horas_legadas'] = $horasManuais;
        $dados['carga_horaria_cumprida'] = $horasManuais + $horasAulas;
        $ofertas_disciplina->update($dados);

        /*
        * ETAPA 4 - REDIRECIONAR
        *
        * Retorna à listagem e apresenta
        * uma mensagem de sucesso.
        */
        return redirect()
            ->route('ofertas-disciplinas.index')
            ->with('success', 'Oferta de disciplina atualizada com sucesso!');
    }


    
    /**
     * EXCLUIR UMA OFERTA DE DISCIPLINA
     *
     * Remove uma oferta específica do banco de dados.
     *
     * Importante:
     * - Não exclui a disciplina cadastrada;
     * - Não exclui o semestre;
     * - Não exclui o docente;
     * - Remove apenas o registro da oferta.
     */
    public function destroy(OfertaDisciplina $ofertas_disciplina)
    {
        /*
        * ETAPA 1 - EXCLUIR A OFERTA
        *
        * delete() remove o registro correspondente
        * da tabela oferta_disciplinas.
        */
        $ofertas_disciplina->delete();

        /*
        * ETAPA 2 - REDIRECIONAR
        *
        * Retorna à listagem e apresenta
        * uma mensagem de confirmação.
        */
        return redirect()
            ->route('ofertas-disciplinas.index')
            ->with('success', 'Oferta de disciplina excluída com sucesso!');
    }

    /**
     * Garante coerência entre curso, período e horas.
     * Ofertas sem vínculo permanecem válidas (registros legados).
     */
    private function validarVinculo(array $dados): void
    {
        $erros = [];
        if (($dados['carga_horaria_cumprida'] ?? 0) > $dados['carga_horaria_prevista']) {
            $erros['carga_horaria_cumprida'] = 'As horas cumpridas não podem superar as previstas.';
        }
        if (!empty($dados['oferta_curso_id'])) {
            $oferta = OfertaCurso::with(['semestre', 'semestreFinal'])->findOrFail($dados['oferta_curso_id']);
            $disciplina = Disciplina::findOrFail($dados['disciplina_id']);
            $periodo = Semestre::findOrFail($dados['semestre_id'])->identificador;
            if ((int) $disciplina->curso_id !== (int) $oferta->curso_id) {
                $erros['oferta_curso_id'] = 'A oferta de curso deve corresponder ao curso da disciplina.';
            }
            $inicio = $oferta->semestre?->identificador;
            $fim = $oferta->semestreFinal?->identificador;
            if (!$inicio || $periodo < $inicio || ($fim && $periodo > $fim)) {
                $erros['semestre_id'] = 'O semestre deve estar dentro do intervalo da oferta de curso.';
            }
        }
        if ($erros) {
            throw ValidationException::withMessages($erros);
        }
    }
}
