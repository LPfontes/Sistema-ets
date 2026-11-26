{{--
=====================================================
SISTEMA ETS/UFPB - CADASTRO DE DISCIPLINAS

Esta página permite cadastrar:
- Nome da disciplina
- Carga horária
- Tipo (EAD ou Presencial)
- Curso
- Módulo curricular

O Controller fornece:
$cursos  = cursos cadastrados
$modulos = módulos cadastrados
=====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Cadastrar Disciplina')

{{-- Inicia o conteúdo --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">
        <h2>Cadastrar Disciplina</h2>

        <p class="text-muted">
            Preencha as informações da nova disciplina.
        </p>
    </div>

    {{-- EXIBIÇÃO DOS ERROS DE VALIDAÇÃO --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Verifique os campos informados:</strong>

            <ul class="mb-0 mt-2">

                {{-- Exibe cada erro retornado pelo Laravel --}}
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    {{-- CARTÃO DO FORMULÁRIO --}}
    <div class="card shadow-sm">

        <div class="card-body p-4">

            {{--
                Envia os dados para store()
                do DisciplinaController.
            --}}
            <form
                action="{{ route('disciplinas.store') }}"
                method="POST"
            >

                {{-- Proteção contra requisições não autorizadas --}}
                @csrf

                <h5 class="text-primary mb-3">
                    Informações da Disciplina
                </h5>

                {{-- NOME DA DISCIPLINA --}}
                <div class="mb-3">

                    <label for="nome" class="form-label">
                        Nome da Disciplina *
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        class="form-control"
                        value="{{ old('nome') }}"
                        placeholder="Ex.: Informática Básica"
                        maxlength="255"
                        required
                    >

                </div>

                {{-- CARGA HORÁRIA --}}
                <div class="mb-3">

                    <label for="carga_horaria" class="form-label">
                        Carga Horária (horas) *
                    </label>

                    <input
                        type="number"
                        id="carga_horaria"
                        name="carga_horaria"
                        class="form-control"
                        value="{{ old('carga_horaria') }}"
                        min="1"
                        placeholder="Ex.: 60"
                        required
                    >

                </div>

                {{-- TIPO DA DISCIPLINA --}}
                <div class="mb-3">

                    <label for="tipo" class="form-label">
                        Tipo da Disciplina *
                    </label>

                    <select
                        id="tipo"
                        name="tipo"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecione o tipo
                        </option>

                        <option
                            value="Presencial"
                            @selected(old('tipo') == 'Presencial')
                        >
                            Presencial
                        </option>

                        <option
                            value="EAD"
                            @selected(old('tipo') == 'EAD')
                        >
                            EAD
                        </option>

                    </select>

                </div>

                                
                
{{--
    CAMPO CATEGORIA DA DISCIPLINA

    O atributo name="categoria" é fundamental:
    ele define o nome usado para enviar
    o valor ao DisciplinaController.

    Os valores precisam ser exatamente:
    Regular ou Complementar.
--}}
<div class="mb-3">

    <label for="categoria" class="form-label">
        Categoria da Disciplina *
    </label>

    <select
        id="categoria"
        name="categoria"
        class="form-select"
        required
    >

        {{-- Opção inicial, sem valor selecionado --}}
        <option value="">
            Selecione a categoria
        </option>

        {{-- Disciplina vinculada a um módulo --}}
        <option
            value="Regular"
            @selected(old('categoria') == 'Regular')
        >
            Regular
        </option>

        {{-- Disciplina que pode não possuir módulo --}}
        <option
            value="Complementar"
            @selected(old('categoria') == 'Complementar')
        >
            Complementar
        </option>

    </select>

</div>



                
                {{--
                    CAMPO CURSO

                    Permite selecionar o curso ao qual
                    a disciplina será vinculada.

                    A variável $cursos é enviada pelo método
                    create() do DisciplinaController.
                --}}
                <div class="mb-3">

                    <label for="curso_id" class="form-label">
                        Curso *
                    </label>

                    <select
                        id="curso_id"
                        name="curso_id"
                        class="form-select"
                        required
                    >

                        {{-- Opção inicial do campo --}}
                        <option value="">
                            Selecione o curso
                        </option>

                        {{--
                            Percorre todos os cursos cadastrados
                            e cria uma opção para cada curso.
                        --}}
                        @foreach ($cursos as $curso)

                            <option
                                value="{{ $curso->id }}"
                                @selected(old('curso_id') == $curso->id)
                            >
                                {{ $curso->nome }}
                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- MÓDULO CURRICULAR --}}
                <div class="mb-3">

                    <label for="modulo_id" class="form-label">
                        Módulo
                    </label>

                    <select
                        id="modulo_id"
                        name="modulo_id"
                        class="form-select"
                    >

                        <option value="">
                            Sem módulo
                        </option>

                        {{--
                            Mostra os módulos cadastrados.

                            Cada módulo possui um curso_id.
                            Exibimos o nome do curso para
                            facilitar a escolha correta.
                        --}}
                        @foreach ($modulos as $modulo)

                            <option
                                value="{{ $modulo->id }}"
                                @selected(old('modulo_id') == $modulo->id)
                            >
                                {{ $modulo->nome }}
                                — {{ $modulo->curso?->nome }}
                            </option>

                        @endforeach

                    </select>

                    <div class="form-text">
                        Selecione um módulo pertencente
                        ao curso escolhido.
                    </div>

                </div>

                {{-- BOTÕES DO FORMULÁRIO --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Volta sem cadastrar --}}
                    <a
                        href="{{ route('disciplinas.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia o formulário para salvar --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar Disciplina
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
    // Aguarda o carregamento dos elementos da página.
    document.addEventListener('DOMContentLoaded', function () {

        // Localiza os campos Categoria e Módulo.
        const categoria = document.getElementById('categoria');
        const modulo = document.getElementById('modulo_id');

        /**
         * Atualiza a obrigatoriedade do módulo
         * conforme a categoria selecionada.
         */
        function atualizarObrigatoriedadeModulo() {

            // Verifica se a categoria escolhida é Regular.
            const disciplinaRegular = categoria.value === 'Regular';

            // Para disciplinas regulares, o módulo é obrigatório.
            // Para complementares, o módulo é opcional.
            modulo.required = disciplinaRegular;
        }

        // Executa a verificação ao abrir o formulário.
        atualizarObrigatoriedadeModulo();

        // Executa novamente quando a categoria mudar.
        categoria.addEventListener(
            'change',
            atualizarObrigatoriedadeModulo
        );
    });
</script>

{{-- Finaliza o conteúdo --}}
@endsection
