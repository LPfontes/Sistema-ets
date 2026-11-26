{{--
    SISTEMA ETS/UFPB
    TESTE DO FORMULÁRIO DE CADASTRO DE CURSOS

    Nesta etapa vamos testar:
    1. O layout principal.
    2. A abertura do formulário.
    3. Os campos de identificação do curso.
--}}

{{-- Carrega o layout principal com o menu lateral --}}
@extends('layouts.app')

{{-- Inicia o conteúdo da página --}}
@section('content')


{{--
VERIFICAÇÃO DOS ERROS DE VALIDAÇÃO

$errors->any():
Verifica se o Laravel encontrou erros
nos dados enviados pelo formulário.
--}}
@if ($errors->any())

    <div class="alert alert-danger">

        <strong>
            Não foi possível cadastrar o curso.
            Verifique os seguintes erros:
        </strong>

        <ul class="mb-0 mt-2">

            {{--
            Percorre todos os erros encontrados
            e apresenta cada mensagem ao usuário.
            --}}
            @foreach ($errors->all() as $erro)

                <li>{{ $erro }}</li>

            @endforeach

        </ul>

    </div>

@endif




<div class="container-fluid">

    {{-- Título da página --}}
    <h2 class="mb-4">Cadastrar Novo Curso</h2>

    {{-- Cartão que contém o formulário --}}
    <div class="card shadow-sm">

        <div class="card-body p-4">

            {{--
                FORMULÁRIO

                action: rota que salvará o curso.
                method POST: envia os dados ao servidor.
            --}}
            <form action="{{ route('cursos.store') }}" method="POST" enctype="multipart/form-data">

                {{-- Protege a requisição contra ataques CSRF --}}
                @csrf

                {{-- PRIMEIRA SEÇÃO: IDENTIFICAÇÃO --}}
                <h5 class="text-primary mb-3">
                    1. Identificação do Curso
                </h5>

                {{-- Organiza os campos em linhas e colunas --}}
                <div class="row g-3">

                    {{-- Campo para informar o nome do curso --}}
                    <div class="col-md-12">
                        <label class="form-label">
                            Nome do Curso *
                        </label>

                        <input
                            type="text"
                            name="nome"
                            class="form-control"
                            value="{{ old('nome') }}"
                            required
                        >
                    </div>

                    {{-- Campo para o código da estrutura curricular --}}
                    {{-- Modalidade do curso: independente do turno. --}}
                    <div class="col-md-6">
                        <label for="tipo_curso" class="form-label">Tipo de Curso *</label>
                        <select name="tipo_curso" id="tipo_curso" class="form-select" required>
                            @foreach (['Presencial' => 'Presencial', 'EAD' => 'EAD (Educação a Distância)', 'Hibrido' => 'Híbrido'] as $valor => $rotulo)
                                <option value="{{ $valor }}" @selected(old('tipo_curso', 'Presencial') === $valor)>{{ $rotulo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Código da Estrutura *
                        </label>

                        <input
                            type="text"
                            name="codigo_estrutura"
                            class="form-control"
                            value="{{ old('codigo_estrutura') }}"
                            required
                        >
                    </div>

                    
                    {{--
                    CAMPO TURNO DO CURSO

                    Este campo permite selecionar o turno.

                    A função old('turno') recupera o valor
                    escolhido anteriormente caso o Laravel
                    retorne ao formulário por um erro.
                    --}}

                    <div class="col-md-6">

                        {{-- Identificação do campo --}}
                        <label for="turno" class="form-label">
                            Turno *
                        </label>

                        {{--
                        name="turno":
                        Nome utilizado pelo Laravel para receber o valor.

                        required:
                        Impede o envio sem selecionar um turno.
                        --}}
                        <select
                            id="turno"
                            name="turno"
                            class="form-select"
                            required
                        >

                            {{-- Opção inicial sem valor --}}
                            <option value="" disabled
                                @selected(old('turno') === null || old('turno') === '')>
                                Selecione o turno
                            </option>

                            {{-- Turno da manhã --}}
                            <option value="Matutino"
                                @selected(old('turno') === 'Matutino')>
                                Matutino
                            </option>

                            {{-- Turno da tarde --}}
                            <option value="Vespertino"
                                @selected(old('turno') === 'Vespertino')>
                                Vespertino
                            </option>

                            {{-- Turno da noite --}}
                            <option value="Noturno"
                                @selected(old('turno') === 'Noturno')>
                                Noturno
                            </option>

                            {{-- Turno integral --}}
                            <option value="Integral"
                                @selected(old('turno') === 'Integral')>
                                Integral
                            </option>

                        </select>

                        {{-- Mostra um erro específico do campo turno --}}
                        @error('turno')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Período em que a estrutura entrou em vigor --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Período de Vigência *
                        </label>

                        <input
                            type="text"
                            name="periodo_vigencia"
                            class="form-control"
                            placeholder="Ex.: 2022.2"
                            value="{{ old('periodo_vigencia') }}"
                            required
                        >
                    </div>

                    {{-- Situação do curso: ativo ou inativo --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Situação *
                        </label>

                        <select name="ativo" class="form-select" required>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>

                </div>

                
                {{--
                ====================================================
                SEÇÃO 2 - ESTRUTURA E CARGA HORÁRIA
                ====================================================

                Esta seção registra as informações sobre
                a duração, a carga horária e a organização
                curricular do curso.

                Esses dados serão enviados ao CursoController
                e armazenados na tabela cursos do MySQL.
                --}}

                {{-- Separa visualmente a primeira e a segunda seção --}}
                <hr class="my-4">

                {{-- Título da segunda seção --}}
                <h5 class="text-primary mb-3">
                    2. Estrutura e Carga Horária
                </h5>

                {{--
                    row: cria uma linha do Bootstrap.
                    g-3: adiciona espaçamento entre os campos.
                --}}
                <div class="row g-3">

                    {{--
                    CAMPO 6 - PRAZO MÍNIMO

                    Informa o tempo mínimo necessário para
                    concluir o curso, em meses.

                    Exemplo: 12 meses.

                    name="prazo_minimo":
                    Corresponde à coluna prazo_minimo
                    da tabela cursos.

                    type="number": aceita números.
                    min="0": impede números negativos.
                    old(): recupera o valor após erro de validação.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Prazo Mínimo (meses)
                        </label>

                        <input
                            type="number"
                            name="prazo_minimo"
                            class="form-control"
                            placeholder="Ex.: 12"
                            value="{{ old('prazo_minimo') }}"
                            min="0"
                            step="1"
                        >

                    </div>

                    {{--
                    CAMPO 7 - PRAZO MÁXIMO

                    Informa o tempo máximo previsto
                    para concluir o curso.

                    Exemplo: 18 meses.

                    O CursoController verificará se
                    o prazo máximo é maior ou igual
                    ao prazo mínimo informado.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Prazo Máximo (meses)
                        </label>

                        <input
                            type="number"
                            name="prazo_maximo"
                            class="form-control"
                            placeholder="Ex.: 18"
                            value="{{ old('prazo_maximo') }}"
                            min="0"
                            step="1"
                        >

                    </div>

                    {{--
                    CAMPO 8 - CARGA HORÁRIA TOTAL

                    Armazena a carga horária oficial do curso.

                    Exemplo: 810 horas.

                    IMPORTANTE:
                    O administrador preencherá este campo
                    manualmente no cadastro do curso.

                    Futuramente, o sistema calculará a soma
                    das cargas horárias das disciplinas para
                    comparar com esse valor oficial.

                    required: preenchimento obrigatório.
                    min="1": exige pelo menos uma hora.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Carga Horária Total (h) *
                        </label>

                        <input
                            type="number"
                            name="carga_horaria_total"
                            class="form-control"
                            placeholder="Ex.: 810"
                            value="{{ old('carga_horaria_total') }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 9 - QUANTIDADE DE MÓDULOS

                    Informa quantos módulos compõem
                    a estrutura curricular do curso.

                    Exemplo:
                    Unidade Curricular I
                    Unidade Curricular II

                    Quantidade de módulos = 2.

                    Observação:
                    Módulos e semestres não serão tratados
                    automaticamente como a mesma coisa.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Quantidade de Módulos *
                        </label>

                        <input
                            type="number"
                            name="quantidade_modulos"
                            class="form-control"
                            placeholder="Ex.: 2"
                            value="{{ old('quantidade_modulos') }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                </div>

                
                    {{--
                    =========================================================
                    SEÇÃO 3 - DOCUMENTAÇÃO DO CURSO
                    =========================================================

                    Esta seção permite registrar informações sobre
                    os documentos oficiais do curso.

                    Teremos dois campos:

                    1. Resolução do Curso.
                    2. Projeto Pedagógico do Curso (PPC).

                    Os dois campos são opcionais, pois a migration
                    permite armazenar valores nulos.
                    --}}


                    {{-- 
                        ETAPA 1 - SEPARAR AS SEÇÕES

                        <hr> cria uma linha horizontal.

                        my-4 adiciona espaçamento acima e abaixo,
                        separando a seção anterior desta seção.
                    --}}
                    <hr class="my-4">


                    {{-- ETAPA 2 - EXIBIR O TÍTULO DA SEÇÃO --}}
                    <h5 class="text-primary mb-3">
                        3. Documentação
                    </h5>


                    {{--
                        ETAPA 3 - ORGANIZAR OS CAMPOS

                        row: cria uma linha do Bootstrap.
                        g-3: define o espaço entre os campos.
                    --}}
                    <div class="row g-3">


                        {{--
                        CAMPO 10 - RESOLUÇÃO DO CURSO

                        Este campo identifica a resolução oficial
                        que regulamenta o curso.

                        Exemplo:
                        Res. CONSEPE 07/2023

                        name="resolucao":
                        Corresponde ao atributo resolucao do Model Curso
                        e à coluna resolucao da tabela cursos.

                        type="text":
                        Permite informar texto.

                        old('resolucao'):
                        Recupera o valor informado anteriormente
                        caso o formulário apresente algum erro.

                        maxlength="255":
                        Limita a quantidade de caracteres.

                        Como não existe required, o campo é opcional.
                        --}}
                        <div class="col-md-12">

                            {{-- Título apresentado acima do campo --}}
                            <label class="form-label">
                                Resolução do Curso
                            </label>

                            {{-- Campo para digitar a resolução --}}
                            <input
                                type="text"
                                name="resolucao"
                                class="form-control"
                                placeholder="Ex.: Res. CONSEPE 07/2023"
                                value="{{ old('resolucao') }}"
                                maxlength="255"
                            >

                        </div>



                        {{-- PPC opcional: enviar arquivo PDF, DOC ou DOCX. --}}
                        <div class="col-md-12">

                            {{-- Título do campo --}}
                            <label class="form-label">
                                Projeto Pedagógico do Curso (PPC)
                            </label>

                            {{-- Campo para informar o endereço do PPC --}}
                            <input type="file" name="projeto_pedagogico" class="form-control" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="text-muted">Opcional. PDF, DOC ou DOCX, até 10 MB.</small>

                        </div>

                    </div>



                    {{--
                    =========================================================
                    ETAPA 4 - BOTÕES DO FORMULÁRIO
                    =========================================================

                    Agora vamos substituir o botão temporário
                    pelos botões definitivos.

                    CANCELAR:
                    Retorna à listagem de cursos.

                    SALVAR CURSO:
                    Envia o formulário para o CursoController.
                    --}}


                    {{-- Linha horizontal que separa os botões dos campos --}}
                    <hr class="my-4">


                    {{--
                        d-flex:
                        Organiza os botões lado a lado.

                        justify-content-end:
                        Alinha os botões à direita.

                        gap-2:
                        Adiciona espaço entre os botões.
                    --}}
                    <div class="d-flex justify-content-end gap-2">


                        {{--
                        BOTÃO CANCELAR

                        A tag <a> cria um link.

                        route('cursos.index'):
                        Direciona para a listagem de cursos.

                        btn-secondary:
                        Aplica a aparência cinza do Bootstrap.

                        IMPORTANTE:
                        Ainda vamos criar a página cursos.index.
                        --}}
                        <a
                            href="{{ route('cursos.index') }}"
                            class="btn btn-secondary"
                        >
                            Cancelar
                        </a>



                        {{--
                        BOTÃO SALVAR CURSO

                        type="submit":
                        Envia os dados preenchidos no formulário.

                        Quando o usuário clicar neste botão:

                        1. O navegador enviará os dados pela rota
                        cursos.store usando o método POST.

                        2. O CursoController executará store().

                        3. O Laravel validará os campos.

                        4. O Model Curso salvará os dados no MySQL.

                        5. O usuário será redirecionado para
                        a página de listagem dos cursos.

                        btn-primary:
                        Aplica a aparência azul do Bootstrap.
                        --}}
                        {{-- Salva o curso e abre a página independente de módulos. --}}
                        <button type="submit" name="acao" value="salvar_e_adicionar_modulo"
                                class="btn btn-outline-primary">
                            + Salvar e Adicionar Módulo
                        </button>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Salvar Curso
                        </button>


                    </div>


            </form>

        </div>

    </div>

</div>

{{-- Finaliza a seção de conteúdo --}}
@endsection
