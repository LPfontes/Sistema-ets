{{--
====================================================
SISTEMA ETS/UFPB - CADASTRO DE SEMESTRES

ARQUIVO: semestres/create.blade.php

OBJETIVO:
Permitir cadastrar um semestre letivo.

REQUISITO US3:
Identificador do semestre, exemplo: 2026.2.

A lista das disciplinas ofertadas será
implementada posteriormente.
====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Cadastrar Semestre')

{{-- Inicia o conteúdo principal --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO DA PÁGINA --}}
    <div class="mb-4">

        <h2>Cadastrar Novo Semestre</h2>

        <p class="text-muted">
            Informe o identificador do semestre letivo.
        </p>

    </div>

    {{--
    MENSAGENS DE ERRO

    Exibe os erros enviados pelo Controller
    quando os dados não passam na validação.
    --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Não foi possível cadastrar o semestre:
            </strong>

            <ul class="mb-0 mt-2">

                {{-- Percorre as mensagens de erro --}}
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

            route('semestres.store'):
            Envia os dados ao método store()
            do SemestreController.

            method="POST":
            Utilizado para criar novos registros.

            @csrf:
            Protege o formulário.
            --}}
            <form
                action="{{ route('semestres.store') }}"
                method="POST"
            >

                @csrf

                <h5 class="text-primary mb-3">
                    Informações do Semestre
                </h5>

                {{--
                IDENTIFICADOR DO SEMESTRE

                Exemplos:
                2026.1
                2026.2
                2027.1

                old('identificador'):
                Recupera o valor digitado caso
                ocorra um erro de validação.

                maxlength="6":
                Limita o campo a seis caracteres.
                --}}
                <div class="mb-3">

                    <label
                        for="identificador"
                        class="form-label"
                    >
                        Identificador do Semestre *
                    </label>

                    <input
                        type="text"
                        id="identificador"
                        name="identificador"
                        class="form-control"
                        placeholder="Ex.: 2026.2"
                        value="{{ old('identificador') }}"
                        maxlength="6"
                        pattern="[0-9]{4}\.[12]"
                        title="Informe no formato AAAA.1 ou AAAA.2"
                        required
                    >

                    {{-- Orientação para preenchimento --}}
                    <div class="form-text">
                        Utilize o formato AAAA.1 ou AAAA.2.
                        Exemplo: 2026.2.
                    </div>

                </div>

                {{-- BOTÕES DO FORMULÁRIO --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Retorna à listagem sem salvar --}}
                    <a
                        href="{{ route('semestres.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia os dados para cadastro --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar Semestre
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo principal --}}
@endsection
