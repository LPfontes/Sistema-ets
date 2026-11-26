<?php

namespace App\Http\Controllers;

// Importa os Models utilizados neste Controller.
use App\Models\Disciplina;
use App\Models\Curso;
use App\Models\Modulo;

// Permite receber e validar os dados dos formulários.
use Illuminate\Http\Request;

// Permite criar regras de validação condicionais.
use Illuminate\Validation\Rule;

// Permite aplicar regras de validação condicionais.
use Illuminate\Validation\Rules\RequiredIf;

class DisciplinaController extends Controller
{
    /**
     * INDEX - LISTAR DISCIPLINAS
     *
     * Busca todas as disciplinas cadastradas.
     *
     * with() carrega os cursos e módulos relacionados,
     * evitando consultas adicionais desnecessárias.
     */
    public function index()
    {
        $disciplinas = Disciplina::with(['curso', 'modulo'])
            ->orderBy('nome')
            ->get();

        // Envia a lista para a página de disciplinas.
        return view('disciplinas.index', compact('disciplinas'));
    }

    /**
     * CREATE - FORMULÁRIO DE CADASTRO
     *
     * Busca os cursos e módulos cadastrados
     * para preencher os campos de seleção.
     */
    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();

        $modulos = Modulo::orderBy('nome')->get();

        // Abre o formulário de nova disciplina.
        return view(
            'disciplinas.create',
            compact('cursos', 'modulos')
        );
    }

    /**
     * STORE - SALVAR DISCIPLINA
     *
     * Recebe os dados enviados pelo formulário,
     * valida as informações e salva no banco.
     */
    public function store(Request $request)
    {
        // Valida os campos antes de cadastrar.
        $dados = $this->validarDisciplina($request);

        // Cria o registro na tabela disciplinas.
        Disciplina::create($dados);

        // Retorna à listagem com mensagem de sucesso.
        return redirect()
            ->route('disciplinas.index')
            ->with('success', 'Disciplina cadastrada com sucesso!');
    }

    /**
     * SHOW - VISUALIZAR DISCIPLINA
     *
     * O Laravel localiza automaticamente a disciplina
     * pelo ID informado na rota.
     */
    public function show(Disciplina $disciplina)
    {
        // Carrega os relacionamentos da disciplina.
        $disciplina->load(['curso', 'modulo']);

        // Abre a página de detalhes.
        return view(
            'disciplinas.show',
            compact('disciplina')
        );
    }

    /**
     * EDIT - FORMULÁRIO DE EDIÇÃO
     *
     * Busca a disciplina selecionada e os dados
     * necessários para preencher os campos.
     */
    public function edit(Disciplina $disciplina)
    {
        $cursos = Curso::orderBy('nome')->get();

        $modulos = Modulo::orderBy('nome')->get();

        // Envia os dados atuais para o formulário.
        return view(
            'disciplinas.edit',
            compact('disciplina', 'cursos', 'modulos')
        );
    }

    /**
     * UPDATE - ATUALIZAR DISCIPLINA
     *
     * Valida as alterações e atualiza o registro.
     */
    public function update(
        Request $request,
        Disciplina $disciplina
    ) {
        // Valida os novos dados.
        $dados = $this->validarDisciplina($request);

        // Atualiza a disciplina existente.
        $disciplina->update($dados);

        return redirect()
            ->route('disciplinas.index')
            ->with('success', 'Disciplina atualizada com sucesso!');
    }

    /**
     * DESTROY - EXCLUIR DISCIPLINA
     *
     * Exclui a disciplina selecionada.
     *
     * Quando implementarmos as ofertas, também
     * protegeremos disciplinas que já possuem
     * registros de ofertas ou cargas horárias.
     */
    public function destroy(Disciplina $disciplina)
    {
        // Exclui o registro do banco de dados.
        $disciplina->delete();

        return redirect()
            ->route('disciplinas.index')
            ->with('success', 'Disciplina excluída com sucesso!');
    }

    
    /**
     * VALIDAÇÃO DOS DADOS DA DISCIPLINA
     *
     * Este método é utilizado tanto no cadastro
     * quanto na edição de disciplinas.
     *
     * REGRAS:
     * 1. Toda disciplina precisa de nome, CH e tipo.
     * 2. Toda disciplina pertence a um curso.
     * 3. A categoria pode ser Regular ou Complementar.
     * 4. Disciplina Regular exige um módulo.
     * 5. Disciplina Complementar pode não ter módulo.
     * 6. O módulo selecionado deve pertencer ao curso.
     */
    private function validarDisciplina(Request $request): array
    {
        return $request->validate([

            // Nome obrigatório da disciplina.
            'nome' => [
                'required',
                'string',
                'max:255',
            ],

            // Carga horária obrigatória e maior que zero.
            'carga_horaria' => [
                'required',
                'integer',
                'min:1',
            ],

            // Modalidade de ensino.
            'tipo' => [
                'required',
                Rule::in(['EAD', 'Presencial']),
            ],

            // Categoria da disciplina.
            'categoria' => [
                'required',
                Rule::in(['Regular', 'Complementar']),
            ],

            // Curso ao qual a disciplina pertence.
            'curso_id' => [
                'required',
                'exists:cursos,id',
            ],

            // Módulo curricular.
            'modulo_id' => [

                // O módulo é obrigatório quando
                // a categoria for Regular.
                new RequiredIf(
                    fn () => $request->input('categoria') === 'Regular'
                ),

                // Permite valor vazio para disciplinas
                // da categoria Complementar.
                'nullable',

                // Se um módulo for informado,
                // ele precisa existir e pertencer
                // ao curso selecionado.
                Rule::exists('modulos', 'id')
                    ->where(
                        'curso_id',
                        $request->input('curso_id')
                    ),
            ],

        ], [

            // Mensagens personalizadas de validação.
            'nome.required' =>
                'Informe o nome da disciplina.',

            'carga_horaria.required' =>
                'Informe a carga horária.',

            'carga_horaria.min' =>
                'A carga horária deve ser maior que zero.',

            'tipo.required' =>
                'Selecione o tipo da disciplina.',

            'tipo.in' =>
                'Selecione EAD ou Presencial.',

            'categoria.required' =>
                'Selecione a categoria da disciplina.',

            'categoria.in' =>
                'Selecione Regular ou Complementar.',

            'curso_id.required' =>
                'Selecione o curso.',

            'curso_id.exists' =>
                'O curso selecionado não existe.',

            'modulo_id.required' =>
                'Selecione um módulo para a disciplina regular.',

            'modulo_id.exists' =>
                'O módulo selecionado deve pertencer ao curso informado.',

        ]);
    }

}
