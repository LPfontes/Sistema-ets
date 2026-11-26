{{--
====================================================
SISTEMA ETS/UFPB - DETALHES DO SEMESTRE

Esta página apresenta:
1. O identificador do semestre.
2. A lista das disciplinas ofertadas
   (será integrada posteriormente).

O Controller envia a variável $semestre.
====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Detalhes do Semestre')

{{-- Inicia o conteúdo principal --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO DA PÁGINA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Detalhes do Semestre</h2>

            <p class="text-muted mb-0">
                Consulte as informações do semestre letivo.
            </p>
        </div>

        {{-- Retorna à listagem de semestres --}}
        <a
            href="{{ route('semestres.index') }}"
            class="btn btn-secondary"
        >
            Voltar
        </a>

    </div>

    {{-- CARTÃO COM OS DADOS DO SEMESTRE --}}
    <div class="card shadow-sm mb-4">

        
{{--
    CABEÇALHO DAS DISCIPLINAS OFERTADAS

    Exibe o título da seção e um botão
    para cadastrar uma nova oferta.

    O parâmetro semestre_id informa ao
    formulário qual semestre foi selecionado.
--}}
<div class="card-header d-flex justify-content-between align-items-center">

            {{-- Título da seção --}}
            <h5 class="mb-0">
                Disciplinas Ofertadas
            </h5>

            {{-- Abre o cadastro com o semestre atual selecionado --}}
            <a
                href="{{ route('ofertas-disciplinas.create', ['semestre_id' => $semestre->id]) }}"
                class="btn btn-sm btn-primary"
            >
                + Nova Oferta
            </a>

        </div>


        <div class="card-body">

          

            {{-- Identificador do período letivo --}}
            <p class="mb-0">
                <strong>Semestre:</strong>
                {{ $semestre->identificador }}
            </p>

        </div>

        {{-- BOTÕES DE NAVEGAÇÃO --}}
        <div class="card-footer d-flex gap-2">

            {{-- Abre o formulário de edição --}}
            <a
                href="{{ route('semestres.edit', $semestre) }}"
                class="btn btn-warning"
            >
                Editar Semestre
            </a>

            {{-- Retorna à listagem --}}
            <a
                href="{{ route('semestres.index') }}"
                class="btn btn-secondary"
            >
                Voltar à Listagem
            </a>

        </div>

    </div>

    
    {{--
    =====================================================
    SISTEMA ETS/UFPB
    DISCIPLINAS OFERTADAS NO SEMESTRE

    Esta seção apresenta as ofertas vinculadas
    ao semestre que está sendo visualizado.

    Os dados são carregados pelo método show()
    do SemestreController.
    =====================================================
    --}}

    <div class="card shadow-sm mt-4">

        {{-- CABEÇALHO DA SEÇÃO --}}
        <div class="card-header">

            <h5 class="mb-0">
                Disciplinas Ofertadas
            </h5>

        </div>

        <div class="card-body">

            {{--
                VERIFICAR SE EXISTEM OFERTAS

                ofertasDisciplinas é o relacionamento
                definido no Model Semestre.

                isEmpty() verifica se a coleção
                não possui registros.
            --}}
            @if ($semestre->ofertasDisciplinas->isEmpty())

                <div class="alert alert-info mb-0">

                    Nenhuma disciplina ofertada neste semestre.

                </div>

            @else

                {{-- Permite rolagem horizontal em telas pequenas --}}
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        {{-- CABEÇALHO DA TABELA --}}
                        <thead class="table-light">

                            <tr>
                                <th>ID</th>
                                <th>Disciplina</th>
                                <th>Docente Responsável</th>
                                <th>Carga Horária Prevista</th>
                                <th>Ações</th>
                            </tr>

                        </thead>

                        <tbody>

                            {{--
                                Percorre todas as ofertas
                                vinculadas ao semestre.
                            --}}
                            @foreach ($semestre->ofertasDisciplinas as $oferta)

                                <tr>

                                    {{-- ID da oferta --}}
                                    <td>
                                        {{ $oferta->id }}
                                    </td>

                                    {{-- Nome da disciplina --}}
                                    <td>
                                        {{ $oferta->disciplina?->nome ?? 'Não informada' }}
                                    </td>

                                    {{-- Docente responsável --}}
                                    <td>
                                        {{ $oferta->docente?->nome ?? 'Não definido' }}
                                    </td>

                                    {{-- Carga horária da oferta --}}
                                    <td>
                                        {{ $oferta->carga_horaria_prevista }}h
                                    </td>

                                    {{-- AÇÕES --}}
                                    <td class="text-nowrap">

                                        {{-- Abre os detalhes da oferta --}}
                                        <a
                                            href="{{ route('ofertas-disciplinas.show', $oferta) }}"
                                            class="btn btn-sm btn-info"
                                        >
                                            Ver Oferta
                                        </a>

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
