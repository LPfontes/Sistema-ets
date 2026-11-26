{{--
====================================================
SISTEMA ETS/UFPB - CADASTRO DE MÓDULOS

ARQUIVO: resources/views/modulos/create.blade.php

OBJETIVO:
Permitir cadastrar um módulo curricular
e vinculá-lo a um curso existente.

DADOS:
- Curso
- Nome do módulo
- Carga horária
- Período de oferta
====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página no navegador --}}
@section('title', 'Cadastrar Módulo - ETS/UFPB')

{{-- Inicia o conteúdo principal da página --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">

        <h2>Cadastrar Novo Módulo</h2>

        <p class="text-muted">
            Informe os dados do módulo curricular.
        </p>

    </div>

    {{--
    EXIBIÇÃO DOS ERROS

    Se algum campo não passar na validação
    do ModuloController, o Laravel retorna
    as mensagens para esta página.
    --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Não foi possível cadastrar o módulo:
            </strong>

            <ul class="mb-0 mt-2">

                {{-- Percorre todos os erros encontrados --}}
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
            FORMULÁRIO DE CADASTRO

            action:
            Envia os dados para a rota modulos.store.

            method="POST":
            Utilizado para cadastrar novos registros.

            @csrf:
            Protege o formulário contra requisições
            não autorizadas.
            --}}
            <form
                action="{{ route('modulos.store') }}"
                method="POST"
            >

                @csrf

                {{-- Mantém o retorno ao curso quando o cadastro começou nele. --}}
                @if (isset($cursoSelecionado) && $cursoSelecionado)
                    <input type="hidden" name="retornar_ao_curso" value="1">
                    <div class="alert alert-info">
                        Cadastrando módulo para o curso: <strong>{{ $cursoSelecionado->nome }}</strong>
                    </div>
                @endif

                <h5 class="text-primary mb-3">
                    Identificação do Módulo
                </h5>

                <div class="row g-3">

                    {{--
                    CAMPO 1 - CURSO

                    $cursos:
                    Lista enviada pelo ModuloController.

                    @foreach:
                    Cria uma opção para cada curso
                    existente no banco de dados.

                    curso_id:
                    Guarda o identificador do curso
                    ao qual o módulo pertence.
                    --}}
                    <div class="col-md-12">

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
                                Selecione um curso
                            </option>

                            @foreach ($cursos as $curso)

                                <option
                                    value="{{ $curso->id }}"
                                    @selected(old('curso_id', $cursoSelecionado->id ?? null) == $curso->id)
                                >
                                    {{ $curso->nome }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{--
                    CAMPO 2 - NOME DO MÓDULO

                    Exemplo:
                    UNIDADE CURRICULAR I

                    old('nome'):
                    Recupera o valor digitado
                    se ocorrer algum erro de validação.
                    --}}
                    <div class="col-md-12">

                        <label for="nome" class="form-label">
                            Nome do Módulo *
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            class="form-control"
                            placeholder="Ex.: UNIDADE CURRICULAR I"
                            value="{{ old('nome') }}"
                            maxlength="255"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 3 - CARGA HORÁRIA

                    Armazena a carga horária oficial
                    do módulo em horas.

                    Exemplo:
                    420

                    min="1":
                    Impede valores menores que 1.
                    --}}
                    <div class="col-md-6">

                        <label for="carga_horaria" class="form-label">
                            Carga Horária (h) *
                        </label>

                        <input
                            type="number"
                            id="carga_horaria"
                            name="carga_horaria"
                            class="form-control"
                            placeholder="Ex.: 420"
                            value="{{ old('carga_horaria') }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 4 - PERÍODO DE OFERTA

                    Representa a ordem de oferta
                    informada na estrutura curricular.

                    Exemplos:
                    1 = primeiro período de oferta
                    2 = segundo período de oferta

                    Não é necessariamente o semestre
                    acadêmico, como 2026.2.
                    --}}
                    <div class="col-md-6">

                        <label for="periodo_oferta" class="form-label">
                            Período de Oferta *
                        </label>

                        <input
                            type="number"
                            id="periodo_oferta"
                            name="periodo_oferta"
                            class="form-control"
                            placeholder="Ex.: 1, 2, 3"
                            value="{{ old('periodo_oferta') }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                </div>

                {{-- BOTÕES DO FORMULÁRIO --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{--
                    CANCELAR:
                    Retorna à listagem de módulos.
                    --}}
                    <a
                        href="{{ isset($cursoSelecionado) && $cursoSelecionado ? route('cursos.edit', $cursoSelecionado) : route('modulos.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{--
                    SALVAR:
                    Envia os dados ao método store()
                    do ModuloController.
                    --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar Módulo
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo da página --}}
@endsection
