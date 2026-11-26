{{--
====================================================
SISTEMA ETS/UFPB - LISTAGEM DE DISCIPLINAS

Arquivo: resources/views/disciplinas/index.blade.php

Objetivo:
- Listar as disciplinas cadastradas.
- Mostrar curso, módulo, carga horária e tipo.
- Permitir visualizar, editar e excluir disciplinas.

A variável $disciplinas é enviada pelo
método index() do DisciplinaController.
====================================================
--}}

{{-- Reutiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Gerenciamento de Disciplinas')

{{-- Inicia o conteúdo principal --}}
@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="container-fluid">

    {{-- CABEÇALHO DA PÁGINA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Gerenciamento de Disciplinas</h2>

            <p class="text-muted mb-0">
                Consulte e gerencie as disciplinas dos cursos.
            </p>
        </div>

        {{-- Direciona para o formulário de cadastro --}}
        <a href="{{ route('disciplinas.create') }}"
           class="btn btn-primary">
            + Nova Disciplina
        </a>

    </div>

    {{--
        MENSAGEM DE SUCESSO

        Exibe mensagens enviadas pelo Controller
        após cadastrar, editar ou excluir registros.
    --}}
    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    {{-- CARTÃO DA LISTAGEM --}}
    <div class="card shadow-sm">

        <div class="card-body">

            {{--
                Verifica se existem disciplinas cadastradas.

                Se a coleção estiver vazia, mostra
                uma mensagem informativa.
            --}}
            @if ($disciplinas->isEmpty())

                <div class="alert alert-info mb-0">
                    Nenhuma disciplina cadastrada até o momento.
                </div>

            @else

                {{-- Permite rolagem horizontal em telas menores --}}
                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle ets-listagem">

                        {{-- CABEÇALHO DA TABELA --}}
                        <thead class="table-light">

                            <tr>

                                <th>Disciplina</th>
                                <th>CH</th>
                                <th>Tipo</th>
                                <th>Categoria</th>
                                <th>Curso</th>
                                <th>Módulo</th>
                                <th>Ações</th>
                            </tr>

                        </thead>

                        {{-- CORPO DA TABELA --}}
                        <tbody>

                            {{--
                                Percorre todas as disciplinas
                                retornadas pelo Controller.
                            --}}
                            @foreach ($disciplinas as $disciplina)

                                <tr>
                                    {{-- Nome da disciplina --}}
                                    <td>
                                        <a class="ets-nome-link" href="{{ route('disciplinas.show', $disciplina) }}">{{ $disciplina->nome }}</a>
                                    </td>

                                    {{-- Carga horária em horas --}}
                                    <td>
                                        {{ $disciplina->carga_horaria }}h
                                    </td>

                                    {{-- Tipo: EAD ou Presencial --}}
                                    <td>
                                        {{ $disciplina->tipo }}
                                    </td>

                                    
                                    {{--
                                        COLUNA CATEGORIA

                                        Exibe a categoria salva no banco de dados.

                                        Regular:
                                        Disciplina vinculada a um módulo.

                                        Complementar:
                                        Disciplina que pode não possuir módulo.
                                    --}}
                                    <td>

                                        {{-- Verifica se a disciplina é Regular --}}
                                        @if ($disciplina->categoria === 'Regular')

                                            {{-- Identificação visual da categoria Regular --}}
                                            <span class="badge bg-primary">
                                                Regular
                                            </span>

                                        {{-- Verifica se a disciplina é Complementar --}}
                                        @elseif ($disciplina->categoria === 'Complementar')

                                            {{-- Identificação visual da categoria Complementar --}}
                                            <span class="badge bg-success">
                                                Complementar
                                            </span>

                                        @else

                                            {{-- Mensagem para categorias não reconhecidas --}}
                                            <span class="badge bg-secondary">
                                                Não informada
                                            </span>

                                        @endif

                                    </td>


                                    {{--
                                        Nome do curso relacionado.

                                        O operador ?-> evita erros
                                        caso o relacionamento não exista.
                                    --}}
                                    <td>
                                        {{ $disciplina->curso?->nome ?? 'Não informado' }}
                                    </td>

                                    {{--
                                        Nome do módulo relacionado.

                                        Como modulo_id é opcional,
                                        exibimos um texto alternativo.
                                    --}}
                                    <td>
                                        {{ $disciplina->modulo?->nome ?? 'Sem módulo' }}
                                    </td>

                                    
{{-- 
    COLUNA AÇÕES

    text-nowrap:
    Impede que o conteúdo da coluna
    seja quebrado em várias linhas.
--}}
<td class="text-nowrap">

    {{--
        d-inline-flex:
        Coloca os botões na mesma linha.

        gap-1:
        Adiciona um pequeno espaço entre eles.

        align-items-center:
        Mantém todos alinhados verticalmente.
    --}}
    <div class="d-inline-flex align-items-center gap-1">

        {{-- BOTÃO VER --}}
        <a
            href="{{ route('disciplinas.show', $disciplina) }}"
            class="btn btn-sm btn-info"
        >
            Ver
        </a>

        {{-- BOTÃO EDITAR --}}
        <a
            href="{{ route('disciplinas.edit', $disciplina) }}"
            class="btn btn-sm btn-warning"
        >
            Editar
        </a>

        {{--
            BOTÃO EXCLUIR

            O formulário utiliza POST,
            mas @method('DELETE') informa ao
            Laravel que é uma exclusão.

            A confirmação evita exclusões acidentais.
        --}}
        <form
            action="{{ route('disciplinas.destroy', $disciplina) }}"
            method="POST"
            class="d-inline-flex m-0"
            onsubmit="return confirm('Deseja realmente excluir esta disciplina?');"
        >

            {{-- Proteção CSRF --}}
            @csrf

            {{-- Método HTTP DELETE --}}
            @method('DELETE')

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

{{-- Finaliza o conteúdo principal --}}
@endsection
