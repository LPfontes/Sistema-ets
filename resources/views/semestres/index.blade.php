{{--
    SISTEMA ETS/UFPB
    Página de gerenciamento de semestres.

    Exibe os semestres cadastrados
    e permite acessar as operações do CRUD.
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Gerenciamento de Semestres')

{{-- Inicia o conteúdo da página --}}
@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="container-fluid">

    {{-- Cabeçalho e botão de cadastro --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Gerenciamento de Semestres</h2>

            <p class="text-muted mb-0">
                Consulte e gerencie os semestres letivos.
            </p>
        </div>

        {{-- Abre o formulário de cadastro --}}
        <a href="{{ route('semestres.create') }}"
           class="btn btn-primary">
            + Novo Semestre
        </a>

    </div>

    {{--
        Exibe a mensagem de sucesso enviada
        pelo Controller após uma operação.
    --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Cartão que contém a listagem --}}
    <div class="card shadow-sm">

        <div class="card-body">

            {{--
                Verifica se existem semestres cadastrados.

                Se a coleção estiver vazia,
                mostra uma mensagem informativa.
            --}}
            @if ($semestres->isEmpty())

                <div class="alert alert-info mb-0">
                    Nenhum semestre cadastrado até o momento.
                </div>

            @else

                {{-- Tabela responsiva de semestres --}}
                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle ets-listagem">

                        <thead class="table-light">
                            <tr>

                                <th>Semestre</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{--
                                Percorre os semestres enviados
                                pelo método index() do Controller.
                            --}}
                            @foreach ($semestres as $semestre)

                                <tr>
                                    {{-- Exemplo: 2026.2 --}}
                                    <td>
                                        <a class="ets-nome-link" href="{{ route('semestres.show', $semestre) }}">{{ $semestre->identificador }}</a>
                                    </td>

                                    
                                    {{-- Operações disponíveis --}}
                                    <td>

                                        {{-- Visualizar detalhes --}}
                                        <a href="{{ route('semestres.show', $semestre) }}"
                                           class="btn btn-sm btn-info">
                                            Ver
                                        </a>

                                        {{-- Editar semestre --}}
                                        <a href="{{ route('semestres.edit', $semestre) }}"
                                           class="btn btn-sm btn-warning">
                                            Editar
                                        </a>

                                        {{--
                                            Formulário de exclusão.

                                            @csrf protege a requisição.

                                            @method('DELETE') informa
                                            que queremos excluir o registro.

                                            confirm() pede confirmação
                                            antes de enviar a solicitação.
                                        --}}
                                        <form
                                            action="{{ route('semestres.destroy', $semestre) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Deseja realmente excluir este semestre?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger">
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

{{-- Finaliza o conteúdo --}}
@endsection
