{{--
    SISTEMA ETS/UFPB
    PÁGINA DE DETALHES DA OFERTA

    Mostra os dados da oferta cadastrada.
--}}
@extends('layouts.app')

@section('title', 'Detalhes da Oferta')

@section('content')

<div class="container-fluid">

    {{-- Cabeçalho --}}
    <div class="mb-4">
        <h2>Detalhes da Oferta de Disciplina</h2>
        <p class="text-muted">
            Consulte as informações da oferta cadastrada.
        </p>
    </div>

    {{-- Cartão com as informações --}}
    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">
                Oferta #{{ $oferta->id }}
            </h5>
        </div>

        <div class="card-body">

            {{-- Nome da disciplina --}}
            <p>
                <strong>Disciplina:</strong>
                {{ $oferta->disciplina?->nome ?? 'Não informada' }}
            </p>

            {{-- Período letivo --}}
            <p>
                <strong>Semestre:</strong>
                {{ $oferta->semestre?->identificador ?? 'Não informado' }}
            </p>

            {{-- Professor responsável --}}
            <p>
                <strong>Docente:</strong>
                {{ $oferta->docente?->nome ?? 'Ainda não definido' }}
            </p>

            {{-- Horas atribuídas à oferta --}}
            <p>
                <strong>Carga Horária Prevista:</strong>
                {{ $oferta->carga_horaria_prevista }} horas
            </p>

        </div>

        {{-- Botão para voltar à listagem --}}
        <div class="card-footer">
            <a
                href="{{ route('ofertas-disciplinas.index') }}"
                class="btn btn-secondary"
            >
                Voltar à Listagem
            </a>
        </div>

    </div>

</div>

@endsection
