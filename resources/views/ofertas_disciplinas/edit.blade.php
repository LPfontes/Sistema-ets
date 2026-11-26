{{--
=====================================================
SISTEMA ETS/UFPB
EDITAR OFERTA DE DISCIPLINA

Esta página permite alterar os dados de uma
oferta de disciplina já cadastrada.

Variáveis recebidas do Controller:
- $oferta
- $disciplinas
- $semestres
- $docentes
=====================================================
--}}

{{-- Reutiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Editar Oferta de Disciplina')

{{-- Inicia o conteúdo --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">

        <h2>Editar Oferta de Disciplina</h2>

        <p class="text-muted">
            Atualize as informações da oferta
            número {{ $oferta->id }}.
        </p>

    </div>

    {{-- EXIBE ERROS DE VALIDAÇÃO --}}
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
                FORMULÁRIO DE EDIÇÃO

                A rota update recebe o ID da oferta.

                @csrf protege a requisição.

                @method('PUT') informa ao Laravel
                que estamos atualizando um registro.
            --}}
            <form
                action="{{ route('ofertas-disciplinas.update', $oferta) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                {{-- DISCIPLINA --}}
                <div class="mb-3">

                    <label for="disciplina_id" class="form-label">
                        Disciplina *
                    </label>

                    <select
                        id="disciplina_id"
                        name="disciplina_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecione uma disciplina
                        </option>

                        {{-- Lista as disciplinas cadastradas --}}
                        @foreach ($disciplinas as $disciplina)

                            <option
                                value="{{ $disciplina->id }}"
                                data-carga-horaria="{{ $disciplina->carga_horaria }}"
                                @selected(old('disciplina_id', $oferta->disciplina_id) == $disciplina->id)
                            >
                                {{ $disciplina->nome }}
                                — {{ $disciplina->curso?->nome }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- SEMESTRE --}}
                <div class="mb-3">

                    <label for="semestre_id" class="form-label">
                        Semestre *
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

                        {{-- Lista os semestres cadastrados --}}
                        @foreach ($semestres as $semestre)

                            <option
                                value="{{ $semestre->id }}"
                                @selected(old('semestre_id', $oferta->semestre_id) == $semestre->id)
                            >
                                {{ $semestre->identificador }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Associação opcional com uma oferta específica do curso. --}}
                <div class="mb-3">
                    <label for="oferta_curso_id" class="form-label">Oferta de Curso (turma/coorte)</label>
                    <select id="oferta_curso_id" name="oferta_curso_id" class="form-select">
                        <option value="">Sem vínculo (oferta antiga ou avulsa)</option>
                        @foreach ($ofertasCursos as $ofertaCurso)
                            <option value="{{ $ofertaCurso->id }}"
                                @selected(old('oferta_curso_id', $oferta->oferta_curso_id) == $ofertaCurso->id)>
                                #{{ $ofertaCurso->id }} — {{ $ofertaCurso->curso?->nome }}
                                ({{ $ofertaCurso->semestre?->identificador }} a {{ $ofertaCurso->semestreFinal?->identificador ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Escolha a oferta correta para que os extratos não misturem turmas diferentes.</div>
                </div>

                {{-- DOCENTE RESPONSÁVEL --}}
                <div class="mb-3">

                    <label for="docente_id" class="form-label">
                        Docente Responsável
                    </label>

                    <select
                        id="docente_id"
                        name="docente_id"
                        class="form-select"
                    >

                        <option value="">
                            Docente ainda não definido
                        </option>

                        {{-- Lista os docentes cadastrados --}}
                        @foreach ($docentes as $docente)

                            <option
                                value="{{ $docente->id }}"
                                @selected(old('docente_id', $oferta->docente_id) == $docente->id)
                            >
                                {{ $docente->nome }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- CARGA HORÁRIA PREVISTA --}}
                <div class="mb-3">

                    <label for="carga_horaria_prevista" class="form-label">
                        Carga Horária Prevista (horas) *
                    </label>

                    <input
                        type="number"
                        id="carga_horaria_prevista"
                        name="carga_horaria_prevista"
                        class="form-control"
                        value="{{ old('carga_horaria_prevista', $oferta->carga_horaria_prevista) }}"
                        min="1"
                        required
                    >

                    <div class="form-text">
                        Você pode modificar a carga horária prevista
                        para esta oferta.
                    </div>

                </div>

                {{-- Registro manual das horas efetivamente cumpridas. --}}
                <div class="mb-3">
                    <label for="carga_horaria_cumprida" class="form-label">Horas cumpridas anteriores ao registro de aulas *</label>
                    <input type="number" id="carga_horaria_cumprida" name="carga_horaria_cumprida"
                        class="form-control" min="0" required
                        value="{{ old('carga_horaria_cumprida', $oferta->horas_legadas ?? 0) }}">
                    <div class="form-text">Informe somente horas cumpridas antes do uso do Registro de Aulas. As aulas novas são somadas automaticamente.</div>
                </div>

                {{-- BOTÕES --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Cancela a edição --}}
                    <a
                        href="{{ route('ofertas-disciplinas.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia as alterações --}}
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

{{--
    PREENCHIMENTO AUTOMÁTICO DA CARGA HORÁRIA

    Quando o usuário seleciona outra disciplina,
    o sistema sugere sua carga horária padrão.

    Ao abrir a página, a carga horária já
    cadastrada é preservada.
--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Recupera os campos do formulário.
        const disciplina = document.getElementById('disciplina_id');
        const cargaHoraria = document.getElementById('carga_horaria_prevista');

        // Executa quando a disciplina é alterada.
        disciplina.addEventListener('change', function () {

            // Obtém a opção selecionada.
            const opcao = disciplina.options[disciplina.selectedIndex];

            // Recupera a carga horária padrão da disciplina.
            const horas = opcao.dataset.cargaHoraria;

            // Sugere o novo valor.
            cargaHoraria.value = horas || '';

        });

    });
</script>

{{-- Finaliza o conteúdo --}}
@endsection
