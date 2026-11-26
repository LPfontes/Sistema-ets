{{--
=====================================================
SISTEMA ETS/UFPB
ARQUIVO: cursos/show.blade.php

OBJETIVO:
Mostrar todas as informações de um curso cadastrado.

O CursoController envia a variável $curso
para esta página através do método show().
=====================================================
--}}

{{-- ETAPA 1: Carregar o layout principal do sistema --}}
@extends('layouts.app')

{{-- ETAPA 2: Iniciar o conteúdo da página --}}
@section('content')

<div class="container-fluid">

    {{--
    ETAPA 3: Cabeçalho da página

    Exibe o título e um botão para retornar
    à listagem de cursos.
    --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Detalhes do Curso</h2>

        {{-- Retorna à página de gerenciamento de cursos --}}
        <a href="{{ route('cursos.index') }}"
           class="btn btn-secondary">
            Voltar
        </a>

    </div>

    {{-- ETAPA 4: Criar o cartão de informações --}}
    <div class="card shadow-sm">

        <div class="card-body p-4">

            {{-- SEÇÃO 1: IDENTIFICAÇÃO DO CURSO --}}
            <h5 class="text-primary mb-3">
                1. Identificação do Curso
            </h5>

            <div class="row g-3">

                {{-- Exibe o nome armazenado no banco --}}
                <div class="col-md-12">
                    <strong>Nome do Curso:</strong>
                    <p>{{ $curso->nome }}</p>
                </div>

                {{-- Exibe o código da estrutura curricular --}}
                <div class="col-md-6">
                    <strong>Código da Estrutura:</strong>
                    <p>{{ $curso->codigo_estrutura }}</p>
                </div>

                {{-- Exibe o turno do curso --}}
                <div class="col-md-6">
                    <strong>Turno:</strong>
                    <p>{{ $curso->turno }}</p>
                </div>

                {{-- Exibe o período de vigência --}}
                <div class="col-md-6">
                    <strong>Período de Vigência:</strong>
                    <p>{{ $curso->periodo_vigencia }}</p>
                </div>

                {{-- Verifica se o curso está ativo ou inativo --}}
                <div class="col-md-6">
                    <strong>Situação:</strong>

                    <p>
                        @if ($curso->ativo)
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-secondary">Inativo</span>
                        @endif
                    </p>
                </div>

            </div>

            {{-- SEÇÃO 2: ESTRUTURA E CARGA HORÁRIA --}}
            <hr class="my-4">

            <h5 class="text-primary mb-3">
                2. Estrutura e Carga Horária
            </h5>

            <div class="row g-3">

                {{-- Prazo mínimo previsto para conclusão --}}
                <div class="col-md-6">
                    <strong>Prazo Mínimo:</strong>
                    <p>{{ $curso->prazo_minimo ?? 'Não informado' }} meses</p>
                </div>

                {{-- Prazo máximo previsto para conclusão --}}
                <div class="col-md-6">
                    <strong>Prazo Máximo:</strong>
                    <p>{{ $curso->prazo_maximo ?? 'Não informado' }} meses</p>
                </div>

                {{-- Carga horária oficial informada no cadastro --}}
                <div class="col-md-6">
                    <strong>Carga Horária Total:</strong>
                    <p>{{ $curso->carga_horaria_total }} horas</p>
                </div>

                {{-- Quantidade de módulos da estrutura curricular --}}
                <div class="col-md-6">
                    <strong>Quantidade de Módulos:</strong>
                    <p>{{ $curso->quantidade_modulos }}</p>
                </div>

            </div>

            {{-- SEÇÃO 3: DOCUMENTAÇÃO --}}
            <hr class="my-4">

            <h5 class="text-primary mb-3">
                3. Documentação
            </h5>

            <div class="row g-3">

                {{-- Resolução que regulamenta o curso --}}
                <div class="col-md-12">
                    <strong>Tipo de Curso:</strong> {{ $curso->tipo_curso ?? 'Não informado' }}<br>
                    <strong>Resolução do Curso:</strong>
                    <p>{{ $curso->resolucao ?? 'Não informada' }}</p>
                </div>

                {{-- Exibe o link do PPC, caso tenha sido informado --}}
                <div class="col-md-12">
                    <strong>Projeto Pedagógico do Curso (PPC):</strong>

                    <p>
                        @if ($curso->projeto_pedagogico)

                            {{-- Abre o documento em outra aba --}}
                            <a href="{{ filter_var($curso->projeto_pedagogico, FILTER_VALIDATE_URL) ? $curso->projeto_pedagogico : asset('storage/'.$curso->projeto_pedagogico) }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                Visualizar PPC
                            </a>

                        @else

                            Não informado

                        @endif
                    </p>
                </div>

            </div>

            {{-- ETAPA 5: Botões de navegação --}}
            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">

                {{-- Retorna à listagem --}}
                <a href="{{ route('cursos.index') }}"
                   class="btn btn-secondary">
                    Voltar
                </a>

                {{-- Abre a página de edição do curso --}}
                <a href="{{ route('cursos.edit', $curso) }}"
                   class="btn btn-warning">
                    Editar Curso
                </a>

            </div>

        </div>
    </div>

</div>

{{-- Encerra o conteúdo da página --}}
@endsection
