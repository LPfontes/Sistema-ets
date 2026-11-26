{{--
=========================================================
SISTEMA ETS/UFPB - GERENCIAMENTO DE CURSOS

ARQUIVO: resources/views/cursos/index.blade.php

OBJETIVO:
Exibir a lista de cursos cadastrados no banco de dados.

Esta página recebe a variável $cursos enviada
pelo método index() do CursoController.
=========================================================
--}}


{{--
ETAPA 1 - CARREGAR O LAYOUT PRINCIPAL

Reutiliza o menu lateral e o cabeçalho do sistema.
--}}
@extends('layouts.app')


{{-- ETAPA 2 - INICIAR O CONTEÚDO DA PÁGINA --}}
@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="container-fluid">

    {{--
    ETAPA 3 - CABEÇALHO DA PÁGINA

    d-flex:
    Organiza o título e o botão lado a lado.

    justify-content-between:
    Coloca o título à esquerda e o botão à direita.
    --}}
    <div class="d-flex justify-content-between
                align-items-center mb-4">

        {{-- Título da página --}}
        <div>
            <h2>Gerenciamento de Cursos</h2>

            <p class="text-muted mb-0">
                Consulte e gerencie os cursos cadastrados.
            </p>
        </div>

        {{--
        BOTÃO NOVO CURSO

        route('cursos.create'):
        Abre o formulário de cadastro que já criamos.
        --}}
        <a
            href="{{ route('cursos.create') }}"
            class="btn btn-primary"
        >
            + Novo Curso
        </a>

    </div>


    {{--
    ETAPA 4 - MENSAGEM DE SUCESSO

    Após cadastrar, atualizar ou excluir um curso,
    o CursoController pode enviar uma mensagem
    utilizando session('success').

    Se existir, mostramos a mensagem ao usuário.
    --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{--
    ETAPA 5 - CRIAR O CARTÃO DA LISTAGEM

    card:
    Cria uma área visual para a tabela.

    shadow-sm:
    Adiciona uma sombra suave.
    --}}
    <div class="card shadow-sm">

        <div class="card-body">

            {{--
            ETAPA 6 - VERIFICAR SE EXISTEM CURSOS

            $cursos->isEmpty():
            Verifica se a consulta retornou
            algum curso cadastrado.

            Se não houver cursos, mostramos
            uma mensagem informativa.
            --}}
            @if ($cursos->isEmpty())

                <div class="alert alert-info mb-0">

                    Nenhum curso cadastrado até o momento.

                    <a href="{{ route('cursos.create') }}">
                        Cadastre o primeiro curso.
                    </a>

                </div>

            @else

                {{--
                ETAPA 7 - CRIAR A TABELA

                table:
                Aplica o estilo de tabela do Bootstrap.

                table-striped:
                Alterna as cores das linhas.

                table-hover:
                Destaca a linha ao passar o mouse.

                table-responsive:
                Permite rolagem horizontal em telas menores.
                --}}
                <div class="table-responsive">

                    <table class="table table-striped
                                  table-hover align-middle ets-listagem">

                        {{-- CABEÇALHO DA TABELA --}}
                        <thead class="table-light">

                            <tr>

                                <th>Nome do Curso</th>
                                <th>Código</th>
                                <th>Turno</th>
                                <th>Carga Horária</th>
                                <th>Situação</th>
                                <th>Ações</th>
                            </tr>

                        </thead>


                        {{-- CORPO DA TABELA --}}
                        <tbody>

                            {{--
                            ETAPA 8 - PERCORRER OS CURSOS

                            @foreach:
                            Percorre todos os cursos retornados
                            pelo banco de dados.

                            $curso:
                            Representa um curso por vez.
                            --}}
                            @foreach ($cursos as $curso)

                                <tr>
                                    {{-- Nome do curso --}}
                                    <td>
                                        <a class="ets-nome-link" href="{{ route('cursos.show', $curso) }}">{{ $curso->nome }}</a>
                                    </td>

                                    {{-- Código da estrutura curricular --}}
                                    <td>
                                        {{ $curso->codigo_estrutura }}
                                    </td>

                                    {{-- Turno do curso --}}
                                    <td>
                                        {{ $curso->turno }}
                                    </td>

                                    {{-- Carga horária oficial --}}
                                    <td>
                                        {{ $curso->carga_horaria_total }}h
                                    </td>

                                    {{--
                                    SITUAÇÃO DO CURSO

                                    Se ativo for verdadeiro,
                                    mostramos Ativo.

                                    Caso contrário,
                                    mostramos Inativo.
                                    --}}
                                    <td>

                                        @if ($curso->ativo)

                                            <span class="badge bg-success">
                                                Ativo
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inativo
                                            </span>

                                        @endif

                                    </td>


                                    {{--
                                    ETAPA 9 - BOTÕES DE AÇÃO

                                    Visualizar:
                                    Abre a página de detalhes.

                                    Editar:
                                    Abre o formulário de edição.

                                    Excluir:
                                    Envia uma requisição DELETE.

                                    As páginas de detalhes e edição
                                    serão criadas nas próximas etapas.
                                    --}}
                                    <td>

                                        {{-- Botão visualizar --}}
                                        <a
                                            href="{{ route('cursos.show', $curso) }}"
                                            class="btn btn-sm btn-info"
                                        >
                                            Ver
                                        </a>

                                        {{-- Botão editar --}}
                                        <a
                                            href="{{ route('cursos.edit', $curso) }}"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Editar
                                        </a>

                                        {{--
                                        FORMULÁRIO DE EXCLUSÃO

                                        HTML não suporta DELETE diretamente.

                                        Por isso utilizamos POST com
                                        @method('DELETE') para informar
                                        ao Laravel a operação desejada.

                                        @csrf:
                                        Protege a requisição.

                                        onsubmit:
                                        Solicita confirmação antes de excluir.
                                        --}}
                                        <form
                                            action="{{ route('cursos.destroy', $curso) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Deseja realmente excluir este curso?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                            >
                                                Excluir
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

{{-- Encerra o conteúdo da página --}}
@endsection
