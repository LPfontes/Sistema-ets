{{--
==================================================
SISTEMA ETS/UFPB - EDIÇÃO DE MÓDULOS

Permite alterar os dados de um módulo existente.

O ModuloController envia:
- $modulo: módulo que será editado.
- $cursos: cursos disponíveis no sistema.
==================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Editar Módulo')

{{-- Inicia o conteúdo principal --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">

        <h2>Editar Módulo</h2>

        <p class="text-muted">
            Atualize as informações do módulo curricular.
        </p>

    </div>

    {{--
    EXIBIÇÃO DOS ERROS DE VALIDAÇÃO

    Caso os dados sejam inválidos, o Laravel
    retorna as mensagens nesta seção.
    --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Não foi possível atualizar o módulo:
            </strong>

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
            FORMULÁRIO DE EDIÇÃO

            A rota modulos.update identifica
            qual módulo será atualizado.

            @csrf:
            Protege a requisição.

            @method('PUT'):
            Informa ao Laravel que esta
            requisição atualiza um registro.
            --}}
            <form
                action="{{ route('modulos.update', $modulo) }}"
                method="POST"
            >

                @csrf

                @method('PUT')

                <h5 class="text-primary mb-3">
                    Informações do Módulo
                </h5>

                <div class="row g-3">

                    {{--
                    CAMPO 1 - CURSO

                    Mostra todos os cursos disponíveis.

                    old():
                    Recupera o valor enviado anteriormente
                    caso ocorra erro de validação.

                    $modulo->curso_id:
                    Identifica o curso atualmente associado.
                    --}}
                    <div class="col-md-12">

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

                            @foreach ($cursos as $curso)

                                <option
                                    value="{{ $curso->id }}"
                                    @selected(
                                        old('curso_id', $modulo->curso_id) == $curso->id
                                    )
                                >
                                    {{ $curso->nome }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{--
                    CAMPO 2 - NOME DO MÓDULO

                    Exibe o nome atual e permite alterá-lo.
                    --}}
                    <div class="col-md-12">

                        <label for="nome" class="form-label">
                            Nome do Módulo *
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            class="form-control"
                            value="{{ old('nome', $modulo->nome) }}"
                            maxlength="255"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 3 - CARGA HORÁRIA

                    Exibe a carga horária cadastrada.
                    Permite atualizar o valor em horas.
                    --}}
                    <div class="col-md-6">

                        <label for="carga_horaria" class="form-label">
                            Carga Horária (h) *
                        </label>

                        <input
                            type="number"
                            id="carga_horaria"
                            name="carga_horaria"
                            class="form-control"
                            value="{{ old('carga_horaria', $modulo->carga_horaria) }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 4 - PERÍODO DE OFERTA

                    Exibe o período curricular do módulo.

                    Exemplo:
                    1 = primeiro período de oferta.

                    O mesmo período pode ser utilizado
                    por módulos diferentes do mesmo curso.
                    --}}
                    <div class="col-md-6">

                        <label for="periodo_oferta" class="form-label">
                            Período de Oferta *
                        </label>

                        <input
                            type="number"
                            id="periodo_oferta"
                            name="periodo_oferta"
                            class="form-control"
                            value="{{ old('periodo_oferta', $modulo->periodo_oferta) }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                </div>

                {{-- BOTÕES DO FORMULÁRIO --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Cancela a edição e retorna à listagem --}}
                    <a
                        href="{{ route('modulos.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{--
                    Envia os novos dados ao método
                    update() do ModuloController.
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
