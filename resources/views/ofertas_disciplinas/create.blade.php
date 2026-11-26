{{--
=====================================================
SISTEMA ETS/UFPB
CADASTRO DE OFERTAS DE DISCIPLINAS

Este formulário permite selecionar:
- Disciplina
- Semestre letivo
- Docente responsável
- Carga horária prevista

As listas são enviadas pelo método create()
do OfertaDisciplinaController.
=====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Nova Oferta de Disciplina')

{{-- Inicia o conteúdo da página --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="mb-4">

        <h2>Nova Oferta de Disciplina</h2>

        <p class="text-muted">
            Cadastre uma disciplina ofertada em um semestre.
        </p>

    </div>

    {{-- EXIBE OS ERROS DE VALIDAÇÃO --}}
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
                Envia os dados ao método store()
                do OfertaDisciplinaController.

                @csrf protege a requisição.
            --}}
            <form
                action="{{ route('ofertas-disciplinas.store') }}"
                method="POST"
            >

                @csrf

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

                        {{-- Percorre as disciplinas cadastradas --}}
                        @foreach ($disciplinas as $disciplina)

                            <option
                                value="{{ $disciplina->id }}"
                                data-carga-horaria="{{ $disciplina->carga_horaria }}"
                                @selected(old('disciplina_id') == $disciplina->id)
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

                        {{-- Percorre os semestres cadastrados --}}
                        @foreach ($semestres as $semestre)

                            <option
                                value="{{ $semestre->id }}"
                                @selected(old('semestre_id', request('semestre_id')) == $semestre->id)
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
                                @selected(old('oferta_curso_id', null) == $ofertaCurso->id)>
                                #{{ $ofertaCurso->id }} — {{ $ofertaCurso->curso?->nome }}
                                ({{ $ofertaCurso->semestre?->identificador }} a {{ $ofertaCurso->semestreFinal?->identificador ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Escolha a oferta correta para que os extratos não misturem turmas diferentes.</div>
                </div>

                {{-- DOCENTE --}}
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

                        {{-- Percorre os docentes cadastrados --}}
                        @foreach ($docentes as $docente)

                            <option
                                value="{{ $docente->id }}"
                                @selected(old('docente_id') == $docente->id)
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
                        value="{{ old('carga_horaria_prevista') }}"
                        min="1"
                        required
                    >

                    <div class="form-text">
                        A carga horária será sugerida automaticamente
                        ao selecionar uma disciplina, mas poderá ser alterada.
                    </div>

                </div>

                {{-- Registro manual das horas efetivamente cumpridas. --}}
                <div class="mb-3">
                    <label for="carga_horaria_cumprida" class="form-label">Horas cumpridas anteriores ao registro de aulas *</label>
                    <input type="number" id="carga_horaria_cumprida" name="carga_horaria_cumprida"
                        class="form-control" min="0" required
                        value="{{ old('carga_horaria_cumprida', 0) }}">
                    <div class="form-text">Se não houver horas anteriores, deixe 0. Aulas novas serão registradas no módulo Registro de Aulas.</div>
                </div>

                {{-- BOTÕES --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- Retorna à listagem sem salvar --}}
                    <a
                        href="{{ route('ofertas-disciplinas.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- Envia o formulário --}}
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

{{--
    SCRIPT DE PREENCHIMENTO AUTOMÁTICO

    Quando uma disciplina é selecionada,
    sua carga horária padrão é sugerida
    no campo carga_horaria_prevista.

    O usuário poderá modificar esse valor.
--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Obtém os elementos do formulário.
        const disciplina = document.getElementById('disciplina_id');
        const cargaHoraria = document.getElementById('carga_horaria_prevista');

        // Atualiza a carga horária quando a disciplina muda.
        disciplina.addEventListener('change', function () {

            // Recupera a opção selecionada.
            const opcao = disciplina.options[disciplina.selectedIndex];

            // Lê a carga horária armazenada no atributo data.
            const horas = opcao.dataset.cargaHoraria;

            // Preenche o campo com o valor sugerido.
            // Se nenhuma disciplina estiver selecionada, limpa o campo.
            cargaHoraria.value = horas || '';

        });

    });
</script>

{{-- Finaliza o conteúdo --}}
@endsection
