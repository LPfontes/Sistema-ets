{{--
========================================================
SISTEMA ETS/UFPB — EDIÇÃO DE CURSOS
ARQUIVO: resources/views/cursos/edit.blade.php

OBJETIVO:
Exibir os dados de um curso já cadastrado,
permitindo que o administrador os modifique.

O método edit() do CursoController envia
a variável $curso para esta página.
========================================================
--}}

{{--
ETAPA 1 — UTILIZAR O LAYOUT PRINCIPAL

Reaproveita o cabeçalho, o menu lateral
e os estilos do Sistema ETS/UFPB.
--}}
@extends('layouts.app')

{{-- ETAPA 2 — INICIAR A ÁREA DE CONTEÚDO --}}
@section('content')

{{-- Permite adicionar módulos a cursos já cadastrados, sem misturar os formulários. --}}
<div class="mb-3">
    <a class="btn btn-outline-primary"
       href="{{ route('modulos.create', ['curso_id' => $curso->id]) }}">
        + Adicionar Módulo do Curso
    </a>
</div>


<div class="container-fluid">

    {{-- ETAPA 3 — TÍTULO DA PÁGINA --}}
    <div class="mb-4">

        <h2>Editar Curso</h2>

        {{-- Mostra o nome do curso que está sendo editado --}}
        <p class="text-muted">
            Atualizando as informações de:
            <strong>{{ $curso->nome }}</strong>
        </p>

    </div>

    {{--
    ETAPA 4 — EXIBIR ERROS DE VALIDAÇÃO

    Se o usuário informar algum valor inválido,
    o Laravel retorna os erros para esta página.
    --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Verifique os campos informados:</strong>

            <ul class="mb-0">

                {{-- Percorre todas as mensagens de erro --}}
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    {{-- ETAPA 5 — CARTÃO DO FORMULÁRIO --}}
    <div class="card shadow-sm">

        <div class="card-body p-4">

            {{--
            ETAPA 6 — FORMULÁRIO DE ATUALIZAÇÃO

            route('cursos.update', $curso):
            Identifica qual curso será atualizado.

            method="POST" enctype="multipart/form-data":
            Os formulários HTML aceitam POST,
            mas não aceitam PUT diretamente.

            @method('PUT'):
            Informa ao Laravel que esta requisição
            representa uma atualização.
            --}}
            <form
                action="{{ route('cursos.update', $curso) }}"
                method="POST" enctype="multipart/form-data"
            >

                {{-- Token de proteção da requisição --}}
                @csrf

                {{-- Define o método HTTP de atualização --}}
                @method('PUT')

                {{--
                ==========================================
                SEÇÃO 1 — IDENTIFICAÇÃO DO CURSO
                ==========================================
                --}}

                <h5 class="text-primary mb-3">
                    1. Identificação do Curso
                </h5>

                <div class="row g-3">

                    {{--
                    CAMPO 1 — NOME DO CURSO

                    old('nome', $curso->nome):
                    Se houver erro, mantém o valor digitado.
                    Caso contrário, apresenta o valor
                    atualmente salvo no banco de dados.
                    --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Nome do Curso *
                        </label>

                        <input
                            type="text"
                            name="nome"
                            class="form-control"
                            value="{{ old('nome', $curso->nome) }}"
                            maxlength="255"
                            required
                        >

                    </div>

                    {{-- CAMPO 2 — CÓDIGO DA ESTRUTURA --}}
                    {{-- Modalidade do curso: independente do turno. --}}
                    <div class="col-md-6">
                        <label for="tipo_curso" class="form-label">Tipo de Curso *</label>
                        <select name="tipo_curso" id="tipo_curso" class="form-select" required>
                            @foreach (['Presencial' => 'Presencial', 'EAD' => 'EAD (Educação a Distância)', 'Hibrido' => 'Híbrido'] as $valor => $rotulo)
                                <option value="{{ $valor }}" @selected(old('tipo_curso', $curso->tipo_curso ?? 'Presencial') === $valor)>{{ $rotulo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Código da Estrutura *
                        </label>

                        <input
                            type="text"
                            name="codigo_estrutura"
                            class="form-control"
                            value="{{ old('codigo_estrutura', $curso->codigo_estrutura) }}"
                            maxlength="30"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 3 — TURNO

                    Percorre as opções disponíveis
                    e seleciona o turno atual do curso.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">Turno *</label>

                        <select
                            name="turno"
                            class="form-select"
                            required
                        >

                            @foreach (
                                ['Matutino', 'Vespertino',
                                 'Noturno', 'Integral'] as $turno
                            )

                                <option
                                    value="{{ $turno }}"
                                    @selected(old('turno', $curso->turno) == $turno)
                                >
                                    {{ $turno }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- CAMPO 4 — PERÍODO DE VIGÊNCIA --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Período de Vigência *
                        </label>

                        <input
                            type="text"
                            name="periodo_vigencia"
                            class="form-control"
                            value="{{ old('periodo_vigencia', $curso->periodo_vigencia) }}"
                            pattern="[0-9]{4}\.[12]"
                            maxlength="6"
                            required
                        >

                    </div>

                    {{--
                    CAMPO 5 — SITUAÇÃO

                    O campo ativo é booleano:
                    1 = Ativo
                    0 = Inativo

                    A conversão para string permite
                    comparar os valores do select.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">Situação *</label>

                        <select
                            name="ativo"
                            class="form-select"
                            required
                        >

                            <option
                                value="1"
                                @selected((string) old('ativo', (int) $curso->ativo) === '1')
                            >
                                Ativo
                            </option>

                            <option
                                value="0"
                                @selected((string) old('ativo', (int) $curso->ativo) === '0')
                            >
                                Inativo
                            </option>

                        </select>

                    </div>

                </div>

                {{--
                ==========================================
                SEÇÃO 2 — ESTRUTURA E CARGA HORÁRIA
                ==========================================
                --}}

                <hr class="my-4">

                <h5 class="text-primary mb-3">
                    2. Estrutura e Carga Horária
                </h5>

                <div class="row g-3">

                    {{-- CAMPO 6 — PRAZO MÍNIMO --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Prazo Mínimo (meses)
                        </label>

                        <input
                            type="number"
                            name="prazo_minimo"
                            class="form-control"
                            value="{{ old('prazo_minimo', $curso->prazo_minimo) }}"
                            min="0"
                            step="1"
                        >

                    </div>

                    {{-- CAMPO 7 — PRAZO MÁXIMO --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Prazo Máximo (meses)
                        </label>

                        <input
                            type="number"
                            name="prazo_maximo"
                            class="form-control"
                            value="{{ old('prazo_maximo', $curso->prazo_maximo) }}"
                            min="0"
                            step="1"
                        >

                    </div>

                    {{--
                    CAMPO 8 — CARGA HORÁRIA TOTAL

                    Exibe a carga horária oficial
                    anteriormente cadastrada.

                    O administrador pode alterá-la
                    quando houver uma correção
                    na informação oficial.
                    --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Carga Horária Total (h) *
                        </label>

                        <input
                            type="number"
                            name="carga_horaria_total"
                            class="form-control"
                            value="{{ old('carga_horaria_total', $curso->carga_horaria_total) }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                    {{-- CAMPO 9 — QUANTIDADE DE MÓDULOS --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Quantidade de Módulos *
                        </label>

                        <input
                            type="number"
                            name="quantidade_modulos"
                            class="form-control"
                            value="{{ old('quantidade_modulos', $curso->quantidade_modulos) }}"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                </div>

                {{--
                ==========================================
                SEÇÃO 3 — DOCUMENTAÇÃO
                ==========================================
                --}}

                <hr class="my-4">

                <h5 class="text-primary mb-3">
                    3. Documentação
                </h5>

                <div class="row g-3">

                    {{-- CAMPO 10 — RESOLUÇÃO DO CURSO --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Resolução do Curso
                        </label>

                        <input
                            type="text"
                            name="resolucao"
                            class="form-control"
                            value="{{ old('resolucao', $curso->resolucao) }}"
                            maxlength="255"
                        >

                    </div>

                    {{--
                    CAMPO 11 — PROJETO PEDAGÓGICO

                    Permite atualizar o endereço
                    eletrônico do documento PPC.
                    --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Projeto Pedagógico do Curso (PPC)
                        </label>

                        <input type="file" name="projeto_pedagogico" class="form-control" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                            <small class="text-muted">Opcional. PDF, DOC ou DOCX, até 10 MB.</small>
                            @if ($curso->projeto_pedagogico)
                                <div class="mt-2"><small>Arquivo atual: <a href="{{ filter_var($curso->projeto_pedagogico, FILTER_VALIDATE_URL) ? $curso->projeto_pedagogico : asset('storage/'.$curso->projeto_pedagogico) }}" target="_blank" rel="noopener noreferrer">Visualizar PPC</a></small></div>
                            @endifprojeto_pedagogico) }}"
                        >

                    </div>

                </div>

                {{--
                ETAPA 7 — BOTÕES DO FORMULÁRIO

                Cancelar:
                Retorna à página de detalhes do curso.

                Salvar Alterações:
                Envia os novos valores ao método
                update() do CursoController.
                --}}

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Cancela a edição sem salvar alterações --}}
                    <a
                        href="{{ route('cursos.show', $curso) }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia as alterações para o Laravel --}}
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

{{-- Encerra a área de conteúdo --}}
@endsection
