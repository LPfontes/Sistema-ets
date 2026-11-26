{{--
====================================================
SISTEMA ETS/UFPB - EDIÇÃO DE SEMESTRES

ARQUIVO: resources/views/semestres/edit.blade.php

OBJETIVO:
Permitir alterar o identificador de um semestre
já cadastrado no banco de dados.

O SemestreController envia a variável $semestre.
====================================================
--}}

{{-- Reutiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Editar Semestre')

{{-- Inicia o conteúdo principal --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO DA PÁGINA --}}
    <div class="mb-4">

        <h2>Editar Semestre</h2>

        <p class="text-muted">
            Atualize o identificador do semestre letivo.
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
                Não foi possível atualizar o semestre:
            </strong>

            <ul class="mb-0 mt-2">

                {{-- Percorre todas as mensagens de erro --}}
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
            FORMULÁRIO DE EDIÇÃO

            route('semestres.update', $semestre):
            Identifica o semestre que será atualizado.

            method="POST":
            Método utilizado pelo formulário HTML.

            @method('PUT'):
            Informa ao Laravel que estamos
            atualizando um registro existente.

            @csrf:
            Protege a requisição.
            --}}
            <form
                action="{{ route('semestres.update', $semestre) }}"
                method="POST"
            >

                @csrf

                @method('PUT')

                <h5 class="text-primary mb-3">
                    Informações do Semestre
                </h5>

                {{--
                IDENTIFICADOR DO SEMESTRE

                old('identificador', $semestre->identificador):

                Se houver um erro de validação,
                recupera o valor digitado.

                Caso contrário, apresenta o
                identificador atualmente cadastrado.
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
                        value="{{ old('identificador', $semestre->identificador) }}"
                        maxlength="6"
                        pattern="[0-9]{4}\.[12]"
                        title="Informe no formato AAAA.1 ou AAAA.2"
                        required
                    >

                    {{-- Explica o formato aceito --}}
                    <div class="form-text">
                        Utilize o formato AAAA.1 ou AAAA.2.
                        Exemplo: 2026.2.
                    </div>

                </div>

                {{-- BOTÕES DO FORMULÁRIO --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Cancela a edição sem alterar os dados --}}
                    <a
                        href="{{ route('semestres.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{--
                    Envia o formulário para o método
                    update() do SemestreController.
                    --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar Alterações
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo principal --}}
@endsection
