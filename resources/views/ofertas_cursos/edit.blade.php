
{{--
====================================================
SISTEMA ETS/UFPB
EDITAR OFERTA DE CURSO

Permite atualizar uma oferta cadastrada.

Variáveis recebidas do Controller:
- $oferta: oferta que será editada.
- $cursos: cursos cadastrados.
- $semestres: períodos letivos cadastrados.

Funcionalidades:
- Alterar curso.
- Alterar semestre inicial.
- Alterar semestre final.
- Alterar quantidade de turmas.
- Alterar situação.
- Alterar observação.
====================================================
--}}

{{-- Utiliza o layout principal --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Editar Oferta de Curso')

{{-- Inicia o conteúdo --}}
@section('content')

<div class="container-fluid">

    {{-- CABEÇALHO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Editar Oferta de Curso</h2>

            <p class="text-muted mb-0">
                Atualize as informações da oferta selecionada.
            </p>
        </div>

        {{-- BOTÃO VOLTAR --}}
        <a
            href="{{ route('ofertas-cursos.index') }}"
            class="btn btn-secondary"
        >
            Voltar
        </a>

    </div>

    {{-- EXIBIR ERROS DE VALIDAÇÃO --}}
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
                action="{{ route('ofertas-cursos.update', $oferta->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                {{-- ==================================
                     CAMPO CURSO
                ================================== --}}
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

                        @foreach ($cursos as $curso)

                            <option
                                value="{{ $curso->id }}"
                                @selected(old('curso_id', $oferta->curso_id) == $curso->id)
                            >
                                {{ $curso->nome }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- ==================================
                     SEMESTRE INICIAL
                ================================== --}}
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

                {{-- ==================================
                     SEMESTRE FINAL
                ================================== --}}

                {{--
                    NOVO CAMPO

                    Permite alterar o período final
                    da oferta de curso.

                    Exemplo: 2028.1

                    O valor atual é recuperado pelo
                    relacionamento semestreFinal.

                    old() preserva o valor digitado
                    caso ocorra erro de validação.

                    Os períodos intermediários serão
                    gerados pelo Controller após
                    a atualização do método update().
                --}}
                <div class="mb-3">

                    <label for="periodo_final" class="form-label">
                        Semestre Final *
                    </label>

                    <input
                        type="text"
                        id="periodo_final"
                        name="periodo_final"
                        class="form-control @error('periodo_final') is-invalid @enderror"
                        value="{{ old('periodo_final', $oferta->semestreFinal?->identificador) }}"
                        placeholder="Ex.: 2028.1"
                        pattern="[0-9]{4}\.[12]"
                        maxlength="6"
                        required
                    >

                    <small class="text-muted">
                        Informe o período final no formato AAAA.1 ou AAAA.2.
                        Os períodos intermediários serão gerados automaticamente
                        ao salvar as alterações.
                    </small>

                    {{-- Exibe o erro específico do campo --}}
                    @error('periodo_final')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- ==================================
                     QUANTIDADE DE TURMAS
                ================================== --}}
                <div class="mb-3">

                    <label for="quantidade_turmas" class="form-label">
                        Quantidade de Turmas *
                    </label>

                    <input
                        type="number"
                        id="quantidade_turmas"
                        name="quantidade_turmas"
                        class="form-control"
                        value="{{ old('quantidade_turmas', $oferta->quantidade_turmas) }}"
                        min="1"
                        required
                    >

                </div>

                {{-- ==================================
                     SITUAÇÃO
                ================================== --}}
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
                            @selected(old('situacao', $oferta->situacao) == 'Planejada')
                        >
                            Planejada
                        </option>

                        <option
                            value="Em andamento"
                            @selected(old('situacao', $oferta->situacao) == 'Em andamento')
                        >
                            Em andamento
                        </option>

                        <option
                            value="Concluída"
                            @selected(old('situacao', $oferta->situacao) == 'Concluída')
                        >
                            Concluída
                        </option>

                    </select>

                </div>

                {{-- ==================================
                     OBSERVAÇÃO
                ================================== --}}
                <div class="mb-3">

                    <label for="observacao" class="form-label">
                        Observação
                    </label>

                    <textarea
                        id="observacao"
                        name="observacao"
                        class="form-control"
                        rows="3"
                    >{{ old('observacao', $oferta->observacao) }}</textarea>

                </div>

                {{-- ==================================
                     BOTÕES
                ================================== --}}
                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    {{-- CANCELAR EDIÇÃO --}}
                    <a
                        href="{{ route('ofertas-cursos.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                    {{-- SALVAR ALTERAÇÕES --}}
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

{{-- Finaliza o conteúdo --}}
@endsection
