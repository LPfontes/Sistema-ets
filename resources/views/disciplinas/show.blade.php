{{--
====================================================
SISTEMA ETS/UFPB - DETALHES DA DISCIPLINA

Esta página apresenta:
- Nome da disciplina
- Carga horária
- Tipo de ensino
- Categoria
- Curso
- Módulo curricular

A variável $disciplina é enviada pelo método
show() do DisciplinaController.
====================================================
--}}

{{-- Reutiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Detalhes da Disciplina')

{{-- Inicia o conteúdo principal --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Detalhes da Disciplina</h2>

            <p class="text-muted mb-0">
                Consulte as informações da disciplina cadastrada.
            </p>
        </div>

        {{-- Retorna à listagem --}}
        <a href="{{ route('disciplinas.index') }}"
           class="btn btn-secondary">
            Voltar
        </a>

    </div>

    {{-- CARTÃO COM OS DADOS DA DISCIPLINA --}}
    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">
                {{ $disciplina->nome }}
            </h5>
        </div>

        <div class="card-body">

            {{-- Identificador interno --}}
            <p>
                <strong>ID:</strong>
                {{ $disciplina->id }}
            </p>

            {{-- Nome da disciplina --}}
            <p>
                <strong>Nome:</strong>
                {{ $disciplina->nome }}
            </p>

            {{-- Carga horária --}}
            <p>
                <strong>Carga Horária:</strong>
                {{ $disciplina->carga_horaria }} horas
            </p>

            {{-- Modalidade de ensino --}}
            <p>
                <strong>Tipo:</strong>
                {{ $disciplina->tipo }}
            </p>

            {{-- Categoria da disciplina --}}
            <p>
                <strong>Categoria:</strong>

                @if ($disciplina->categoria === 'Regular')

                    <span class="badge bg-primary">
                        Regular
                    </span>

                @elseif ($disciplina->categoria === 'Complementar')

                    <span class="badge bg-success">
                        Complementar
                    </span>

                @endif
            </p>

            {{-- Curso ao qual pertence --}}
            <p>
                <strong>Curso:</strong>
                {{ $disciplina->curso?->nome ?? 'Não informado' }}
            </p>

            {{-- Módulo curricular --}}
            <p class="mb-0">
                <strong>Módulo:</strong>
                {{ $disciplina->modulo?->nome ?? 'Sem módulo' }}
            </p>

        </div>

        {{-- BOTÕES DE NAVEGAÇÃO --}}
        <div class="card-footer d-flex gap-2">

            {{-- Abre o formulário de edição --}}
            <a
                href="{{ route('disciplinas.edit', $disciplina) }}"
                class="btn btn-warning"
            >
                Editar Disciplina
            </a>

            {{-- Retorna à listagem --}}
            <a
                href="{{ route('disciplinas.index') }}"
                class="btn btn-secondary"
            >
                Voltar à Listagem
            </a>

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo principal --}}
@endsection
