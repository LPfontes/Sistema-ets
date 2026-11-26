{{--=====================================================
SISTEMA ETS/UFPB - GERENCIAMENTO DE MÓDULOS

ARQUIVO: resources/views/modulos/index.blade.php

OBJETIVO:
Listar todos os módulos cadastrados no sistema.

O ModuloController envia a variável $modulos
para esta página através do método index().
=====================================================
--}}

{{-- Reutiliza o menu lateral e os estilos do sistema --}}
@extends('layouts.app')

{{-- Define o título da aba do navegador --}}
@section('title', 'Gerenciamento de Módulos - ETS/UFPB')

{{-- Inicia o conteúdo principal da página --}}
@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="container-fluid">

    {{--
    ETAPA 1 - CABEÇALHO

    d-flex:
    Coloca o título e o botão na mesma linha.

    justify-content-between:
    Posiciona o título à esquerda e o botão à direita.
    --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Gerenciamento de Módulos</h2>

            <p class="text-muted mb-0">
                Consulte e gerencie os módulos curriculares.
            </p>
        </div>

        {{--
        BOTÃO NOVO MÓDULO

        Abre o formulário de cadastro de módulos
        que acabamos de implementar.
        --}}
        <a
            href="{{ route('modulos.create') }}"
            class="btn btn-primary"
        >
            + Novo Módulo
        </a>

    </div>

    {{--
    ETAPA 2 - MENSAGEM DE SUCESSO

    O Controller utiliza session('success')
    para informar que um módulo foi cadastrado,
    atualizado ou excluído com sucesso.
    --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- ETAPA 3 - CARTÃO DA LISTAGEM --}}
    <div class="card shadow-sm">

        <div class="card-body">

            {{--
            ETAPA 4 - VERIFICAR SE EXISTEM MÓDULOS

            isEmpty():
            Retorna verdadeiro quando não existem
            registros na coleção $modulos.
            --}}
            @if ($modulos->isEmpty())

                <div class="alert alert-info mb-0">

                    Nenhum módulo cadastrado até o momento.

                    <a href="{{ route('modulos.create') }}">
                        Cadastre o primeiro módulo.
                    </a>

                </div>

            @else

                {{--
                ETAPA 5 - TABELA DE MÓDULOS

                table-striped:
                Alterna a cor das linhas.

                table-hover:
                Destaca a linha ao passar o mouse.

                table-responsive:
                Permite rolagem horizontal
                em dispositivos com telas menores.
                --}}
                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle ets-listagem">

                        {{-- CABEÇALHO DA TABELA --}}
                        <thead class="table-light">

                            <tr>

                                <th>Nome do Módulo</th>
                                <th>Curso</th>
                                <th>Carga Horária</th>
                                <th>Período de Oferta</th>
                                <th>Ações</th>
                            </tr>

                        </thead>

                        {{-- CORPO DA TABELA --}}
                        <tbody>

                            {{--
                            ETAPA 6 - PERCORRER OS MÓDULOS

                            @foreach:
                            Percorre cada módulo enviado
                            pelo ModuloController.

                            $modulo:
                            Representa o módulo da linha atual.
                            --}}
                            @foreach ($modulos as $modulo)

                                <tr>
                                    {{-- Nome do módulo curricular --}}
                                    <td>
                                        <a href="{{ route('modulos.show', $modulo) }}" class="fw-semibold text-decoration-none">{{ $modulo->nome }}</a>
                                    </td>

                                    {{--
                                    CURSO VINCULADO

                                    $modulo->curso:
                                    Acessa o relacionamento belongsTo()
                                    criado no Model Modulo.

                                    ?->nome:
                                    Recupera o nome do curso, caso exista.
                                    --}}
                                    <td>
                                        {{ $modulo->curso?->nome ?? 'Não informado' }}
                                    </td>

                                    {{-- Carga horária oficial do módulo --}}
                                    <td>
                                        {{ $modulo->carga_horaria }}h
                                    </td>

                                    {{-- Período de oferta do módulo --}}
                                    <td>
                                        {{ $modulo->periodo_oferta }}
                                    </td>

                                    {{-- BOTÕES DE AÇÃO --}}
                                    <td>

                                        {{--
                                        VISUALIZAR

                                        A rota já existe, mas a página
                                        de detalhes será criada depois.
                                        --}}
                                        <a
                                            href="{{ route('modulos.show', $modulo) }}"
                                            class="btn btn-sm btn-info"
                                        >
                                            Ver
                                        </a>

                                        {{--
                                        EDITAR

                                        Abre o formulário de edição,
                                        que criaremos nas próximas etapas.
                                        --}}
                                        <a
                                            href="{{ route('modulos.edit', $modulo) }}"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Editar
                                        </a>

                                        {{--
                                        EXCLUIR

                                        O formulário envia uma requisição
                                        DELETE ao ModuloController.

                                        @csrf:
                                        Protege a requisição.

                                        @method('DELETE'):
                                        Informa o método HTTP utilizado.

                                        confirm():
                                        Solicita confirmação antes de excluir.
                                        --}}
                                        <form
                                            action="{{ route('modulos.destroy', $modulo) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Deseja realmente excluir este módulo?');"
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

{{-- Encerra o conteúdo principal --}}
@endsection
