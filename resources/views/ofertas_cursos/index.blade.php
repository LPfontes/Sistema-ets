{{--
====================================================
SISTEMA ETS/UFPB
LISTAGEM DE OFERTAS DE CURSOS

Esta página apresenta as ofertas cadastradas.

Variável recebida do Controller:
$ofertas — coleção de ofertas de cursos.

Relacionamentos utilizados:
- curso: curso ofertado.
- semestre: período letivo inicial.
- semestreFinal: período letivo final.
====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Ofertas de Cursos')

{{-- Inicia o conteúdo --}}
@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="container-fluid">

    {{-- CABEÇALHO DA PÁGINA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Ofertas de Cursos</h2>

            <p class="text-muted mb-0">
                Gerenciamento das ofertas de cursos da ETS/UFPB.
            </p>
        </div>

        {{-- BOTÃO PARA CADASTRAR NOVA OFERTA --}}
        <a
            href="{{ route('ofertas-cursos.create') }}"
            class="btn btn-primary"
        >
            + Nova Oferta
        </a>

    </div>

    {{-- MENSAGEM DE SUCESSO --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- CARTÃO DA LISTAGEM --}}
    <div class="card shadow-sm">

        <div class="card-body">

            {{--
                VERIFICA SE EXISTEM OFERTAS

                isEmpty() retorna verdadeiro quando
                nenhuma oferta foi cadastrada.
            --}}
            @if ($ofertas->isEmpty())

                <div class="text-center py-4">

                    <p class="text-muted">
                        Nenhuma oferta de curso cadastrada.
                    </p>

                    <a
                        href="{{ route('ofertas-cursos.create') }}"
                        class="btn btn-primary"
                    >
                        Cadastrar Primeira Oferta
                    </a>

                </div>

            @else

                {{-- TABELA RESPONSIVA --}}
                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle ets-listagem">

                        {{-- CABEÇALHO DA TABELA --}}
                        <thead class="table-light">

                            <tr>
                                <th>Curso</th>

                                {{-- Período em que a oferta começa --}}
                                <th>Semestre Inicial</th>

                                {{-- NOVO: período em que a oferta termina --}}
                                <th>Semestre Final</th>

                                <th>Turmas</th>
                                <th>Situação</th>
                                <th>Observação</th>
                                <th>Ações</th>
                            </tr>

                        </thead>

                        <tbody>

                            {{-- PERCORRE TODAS AS OFERTAS --}}
                            @foreach ($ofertas as $oferta)

                                <tr>

                                    {{-- NOME DO CURSO --}}
                                    <td>
                                        <a class="ets-nome-link" href="{{ route('ofertas-cursos.show', $oferta) }}">{{ $oferta->curso?->nome ?? 'Não informado' }}</a>
                                    </td>

                                    {{--
                                        SEMESTRE INICIAL

                                        Utiliza o relacionamento semestre
                                        definido no Model OfertaCurso.
                                    --}}
                                    <td>
                                        {{ $oferta->semestre?->identificador ?? 'Não informado' }}
                                    </td>

                                    {{--
                                        SEMESTRE FINAL

                                        Utiliza o relacionamento
                                        semestreFinal do Model OfertaCurso.

                                        ?-> evita erros quando o
                                        relacionamento estiver vazio.

                                        Ofertas antigas podem não ter
                                        semestre final cadastrado.
                                    --}}
                                    <td>
                                        {{ $oferta->semestreFinal?->identificador ?? 'Não informado' }}
                                    </td>

                                    {{-- QUANTIDADE DE TURMAS --}}
                                    <td>
                                        {{ $oferta->quantidade_turmas }}
                                    </td>

                                    {{-- SITUAÇÃO DA OFERTA --}}
                                    <td>

                                        @if ($oferta->situacao === 'Planejada')

                                            <span class="badge bg-secondary">
                                                Planejada
                                            </span>

                                        @elseif ($oferta->situacao === 'Em andamento')

                                            <span class="badge bg-primary">
                                                Em andamento
                                            </span>

                                        @elseif ($oferta->situacao === 'Concluída')

                                            <span class="badge bg-success">
                                                Concluída
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $oferta->situacao }}
                                            </span>

                                        @endif

                                    </td>

                                    {{-- OBSERVAÇÃO --}}
                                    <td>
                                        {{ $oferta->observacao ?: '-' }}
                                    </td>

                                    {{--
                                        COLUNA AÇÕES

                                        Permite visualizar, editar
                                        e excluir uma oferta.
                                    --}}
                                    <td>

                                        {{-- Organiza os botões lado a lado --}}
                                        <div class="d-flex gap-2">

                                            {{-- BOTÃO VISUALIZAR --}}
                                            <a
                                                href="{{ route('ofertas-cursos.show', $oferta->id) }}"
                                                class="btn btn-sm btn-info text-white"
                                            >
                                                Visualizar
                                            </a>

                                            {{-- BOTÃO EDITAR --}}
                                            <a
                                                href="{{ route('ofertas-cursos.edit', $oferta->id) }}"
                                                class="btn btn-sm btn-warning"
                                            >
                                                Editar
                                            </a>

                                            {{--
                                                FORMULÁRIO DE EXCLUSÃO

                                                @csrf protege a requisição.

                                                @method('DELETE') informa
                                                ao Laravel que queremos
                                                excluir o registro.

                                                onsubmit solicita confirmação
                                                antes da exclusão.
                                            --}}
                                            <form
                                                action="{{ route('ofertas-cursos.destroy', $oferta->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Tem certeza de que deseja excluir esta oferta de curso?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                {{-- BOTÃO EXCLUIR --}}
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger"
                                                >
                                                    Excluir
                                                </button>

                                            </form>

                                        </div>

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

{{-- Finaliza o conteúdo --}}
@endsection
