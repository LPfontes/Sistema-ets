{{--
====================================================
SISTEMA ETS/UFPB - EDITAR DISCIPLINA

Este formulário permite atualizar:
- Nome
- Carga horária
- Tipo (EAD ou Presencial)
- Categoria (Regular ou Complementar)
- Curso
- Módulo

O DisciplinaController envia:
$disciplina, $cursos e $modulos.
====================================================
--}}

{{-- Reutiliza o layout principal --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Editar Disciplina')

{{-- Inicia o conteúdo --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">
        <h2>Editar Disciplina</h2>

        <p class="text-muted">
            Atualize as informações da disciplina.
        </p>
    </div>

    {{-- EXIBE ERROS DE VALIDAÇÃO --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Verifique os campos informados:</strong>

            <ul class="mb-0 mt-2">

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
                Envia as alterações para update().

                @csrf protege a requisição.
                @method('PUT') identifica uma atualização.
            --}}
            <form
                action="{{ route('disciplinas.update', $disciplina) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                {{-- NOME --}}
                <div class="mb-3">

                    <label for="nome" class="form-label">
                        Nome da Disciplina *
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        class="form-control"
                        value="{{ old('nome', $disciplina->nome) }}"
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
                        value="{{ old('carga_horaria', $disciplina->carga_horaria) }}"
                        min="1"
                        required
                    >

                </div>

                {{-- TIPO DE ENSINO --}}
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
                            @selected(old('tipo', $disciplina->tipo) == 'Presencial')
                        >
                            Presencial
                        </option>

                        <option
                            value="EAD"
                            @selected(old('tipo', $disciplina->tipo) == 'EAD')
                        >
                            EAD
                        </option>

                    </select>

                </div>

                {{-- CATEGORIA DA DISCIPLINA --}}
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

                        <option value="">
                            Selecione a categoria
                        </option>

                        <option
                            value="Regular"
                            @selected(old('categoria', $disciplina->categoria) == 'Regular')
                        >
                            Regular
                        </option>

                        <option
                            value="Complementar"
                            @selected(old('categoria', $disciplina->categoria) == 'Complementar')
                        >
                            Complementar
                        </option>

                    </select>

                </div>

                {{-- CURSO --}}
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

                        <option value="">
                            Selecione o curso
                        </option>

                        {{-- Percorre os cursos cadastrados --}}
                        @foreach ($cursos as $curso)

                            <option
                                value="{{ $curso->id }}"
                                @selected(old('curso_id', $disciplina->curso_id) == $curso->id)
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

                        {{-- Lista os módulos cadastrados --}}
                        @foreach ($modulos as $modulo)

                            <option
                                value="{{ $modulo->id }}"
                                @selected(old('modulo_id', $disciplina->modulo_id) == $modulo->id)
                            >
                                {{ $modulo->nome }}
                                — {{ $modulo->curso?->nome }}
                            </option>

                        @endforeach

                    </select>

                    <div class="form-text">
                        O módulo é obrigatório para disciplinas regulares.
                        Para complementares, é opcional.
                    </div>

                </div>

                {{-- BOTÕES --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Cancela sem salvar --}}
                    <a
                        href="{{ route('disciplinas.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia as alterações --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar Alterações
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- 
    SCRIPT DE VALIDAÇÃO VISUAL

    Torna o módulo obrigatório quando a categoria
    selecionada é Regular.
--}}
<script>
    // Aguarda a página carregar.
    document.addEventListener('DOMContentLoaded', function () {

        // Obtém os campos do formulário.
        const categoria = document.getElementById('categoria');
        const modulo = document.getElementById('modulo_id');

        // Atualiza a obrigatoriedade do módulo.
        function atualizarModulo() {

            // Verifica se a disciplina é Regular.
            const regular = categoria.value === 'Regular';

            // Exige módulo apenas para Regular.
            modulo.required = regular;
        }

        // Executa ao abrir a página.
        atualizarModulo();

        // Executa quando a categoria for alterada.
        categoria.addEventListener('change', atualizarModulo);
    });
</script>

{{-- Finaliza o conteúdo --}}
@endsection
