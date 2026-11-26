{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o conteúdo da página --}}
@section('content')

<div class="container-fluid">

    {{-- Título principal --}}
    <h2 class="mb-3">
        Análise de Carga Horária por Curso
    </h2>

    {{-- Identificação do curso selecionado --}}
    <div class="alert alert-primary">
        <strong>Curso:</strong> {{ $curso->nome }}
    </div>

    {{-- Explicação dos dados apresentados --}}
    <p class="text-muted">
        Relatório de carga horária por semestre curricular,
        considerando os módulos e as disciplinas cadastradas.
    </p>

    {{-- Tabela com os resultados da análise --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Semestre curricular</th>
                            <th>Qtd. módulos</th>
                            <th>Qtd. disciplinas</th>
                            <th>CH oficial</th>
                            <th>CH disciplinas</th>
                            <th>Diferença</th>
                        </tr>
                    </thead>

                    <tbody>

                        {{-- Percorre cada semestre curricular --}}
                        @forelse ($analise as $dados)

                            <tr>
                                <td>
                                    {{ $dados['periodo'] }}º semestre
                                </td>

                                <td>
                                    {{ $dados['quantidade_modulos'] }}
                                </td>

                                <td>
                                    {{ $dados['quantidade_disciplinas'] }}
                                </td>

                                <td>
                                    {{ $dados['carga_horaria_oficial'] }}h
                                </td>

                                <td>
                                    {{ $dados['carga_horaria_disciplinas'] }}h
                                </td>

                                <td>
                                    {{ $dados['diferenca'] }}h
                                </td>
                            </tr>

                        @empty

                            {{-- Mensagem quando não existem módulos --}}
                            <tr>
                                <td colspan="6" class="text-center">
                                    Nenhum módulo cadastrado para este curso.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    {{-- Totais gerais de todos os semestres --}}
                    <tfoot class="table-secondary fw-bold">
                        <tr>
                            <td>Total geral</td>

                            <td>
                                {{ $analise->sum('quantidade_modulos') }}
                            </td>

                            <td>
                                {{ $analise->sum('quantidade_disciplinas') }}
                            </td>

                            <td>
                                {{ $analise->sum('carga_horaria_oficial') }}h
                            </td>

                            <td>
                                {{ $analise->sum('carga_horaria_disciplinas') }}h
                            </td>

                            <td>
                                {{ $analise->sum('diferenca') }}h
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
    </div>

    {{-- 
    BOTÃO: ESCOLHER OUTRO CURSO

    Retorna à página de seleção de cursos
    para que o professor possa gerar outro extrato.

    Não retorna ao CRUD de cursos.
--}}
<a href="{{ route('extratos.curso') }}"
   class="btn btn-secondary mt-3">
    ← Escolher Outro Curso
</a>
</div>

@endsection
