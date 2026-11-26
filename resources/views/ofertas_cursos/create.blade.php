{{--
====================================================
SISTEMA ETS/UFPB
CADASTRAR OFERTA DE CURSO

Este formulário permite cadastrar uma oferta
de um curso em determinado período letivo.

Dados recebidos do Controller:
- $cursos: cursos cadastrados
- $semestres: períodos letivos cadastrados
====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Nova Oferta de Curso')

{{-- Inicia o conteúdo da página --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">

        <h2>Nova Oferta de Curso</h2>

        <p class="text-muted">
            Cadastre um curso que será ofertado
            em determinado período letivo.
        </p>

    </div>

    {{-- MENSAGENS DE ERRO --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Verifique os campos informados:</strong>

            <ul class="mb-0 mt-2">

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
                FORMULÁRIO

                action: rota responsável pelo cadastro.

                method POST: envia os dados ao servidor.

                @csrf: protege a requisição.
            --}}
            <form
                action="{{ route('ofertas-cursos.store') }}"
                method="POST"
            >

                @csrf

                {{-- CAMPO CURSO --}}
                <div class="mb-3">

                    <label for="curso_id" class="form-label">
                        Curso *
                    </label>

                    <select
                        id="curso_id"
                        name="curso_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecione um curso
                        </option>

                        {{-- Lista os cursos cadastrados --}}
                        @foreach ($cursos as $curso)

                            <option
                                value="{{ $curso->id }}"
                                @selected(old('curso_id') == $curso->id)
                            >
                                {{ $curso->nome }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- CAMPO SEMESTRE INICIAL --}}
                <div class="mb-3">

                    <label for="semestre_id" class="form-label">
                        Semestre Inicial *
                    </label>

                    <select
                        id="semestre_id"
                        name="semestre_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecione um semestre
                        </option>

                        {{-- Lista os períodos letivos cadastrados --}}
                        @foreach ($semestres as $semestre)

                            <option
                                value="{{ $semestre->id }}"
                                @selected(old('semestre_id') == $semestre->id)
                            >
                                {{ $semestre->identificador }}
                            </option>

                        @endforeach

                    </select>

                    <div class="form-text">
                        Informe o período letivo em que
                        o curso começará.
                    </div>

                </div>



{{-- PERÍODO FINAL DA OFERTA --}}
<div class="mb-3">

    <label for="periodo_final" class="form-label">
        Período Final
    </label>

    {{-- O professor informa quando a oferta termina. --}}
    <input
        type="text"
        name="periodo_final"
        id="periodo_final"
        class="form-control @error('periodo_final') is-invalid @enderror"
        value="{{ old('periodo_final') }}"
        placeholder="Ex.: 2028.1"
        pattern="[0-9]{4}\.[12]"
        maxlength="6"
        required
    >

    {{-- Explicação da geração automática. --}}
    <small class="text-muted">
        Informe o período final no formato AAAA.1 ou AAAA.2.
        Os períodos intermediários serão cadastrados automaticamente.
    </small>

    {{-- Exibe possíveis erros de validação. --}}
    @error('periodo_final')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


                {{-- CAMPO QUANTIDADE DE TURMAS --}}
                <div class="mb-3">

                    <label for="quantidade_turmas" class="form-label">
                        Quantidade de Turmas *
                    </label>

                    <input
                        type="number"
                        id="quantidade_turmas"
                        name="quantidade_turmas"
                        class="form-control"
                        value="{{ old('quantidade_turmas', 1) }}"
                        min="1"
                        required
                    >

                    <div class="form-text">
                        Informe quantas turmas serão
                        abertas nesta oferta.
                    </div>

                </div>

                {{-- CAMPO SITUAÇÃO --}}
                <div class="mb-3">

                    <label for="situacao" class="form-label">
                        Situação *
                    </label>

                    <select
                        id="situacao"
                        name="situacao"
                        class="form-select"
                        required
                    >

                        <option
                            value="Planejada"
                            @selected(old('situacao', 'Planejada') == 'Planejada')
                        >
                            Planejada
                        </option>

                        <option
                            value="Em andamento"
                            @selected(old('situacao') == 'Em andamento')
                        >
                            Em andamento
                        </option>

                        <option
                            value="Concluída"
                            @selected(old('situacao') == 'Concluída')
                        >
                            Concluída
                        </option>

                    </select>

                </div>

                {{-- CAMPO OBSERVAÇÃO --}}
                <div class="mb-3">

                    <label for="observacao" class="form-label">
                        Observação
                    </label>

                    <textarea
                        id="observacao"
                        name="observacao"
                        class="form-control"
                        rows="3"
                        placeholder="Informações adicionais sobre a oferta..."
                    >{{ old('observacao') }}</textarea>

                </div>

                {{-- BOTÕES --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Retorna à listagem --}}
                    <a
                        href="{{ route('ofertas-cursos.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia os dados para cadastro --}}
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Salvar Oferta
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo --}}
@endsection
