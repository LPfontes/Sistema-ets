{{--
====================================================
SISTEMA ETS/UFPB
DETALHES DA OFERTA DE CURSO

Esta página apresenta as informações
de uma oferta de curso cadastrada.

Variável recebida do Controller:
$oferta — oferta selecionada pelo usuário.

Relacionamentos:
- curso: informações do curso.
- semestre: período letivo inicial.
- semestreFinal: período letivo final.

====================================================
--}}

{{-- Utiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Detalhes da Oferta de Curso')

{{-- Inicia o conteúdo da página --}}
@section('content')

<div class="container-fluid">

    {{-- ==========================================
         CABEÇALHO DA PÁGINA
    ========================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Detalhes da Oferta de Curso</h2>

            <p class="text-muted mb-0">
                Informações da oferta cadastrada.
            </p>
        </div>

        {{--
            BOTÕES DE NAVEGAÇÃO

            Permitem editar a oferta atual
            ou retornar à listagem.
        --}}
        <div class="d-flex gap-2">

            {{--
                BOTÃO EDITAR

                Abre o formulário de edição da oferta
                identificada por $oferta->id.
            --}}
            <a
                href="{{ route('ofertas-cursos.edit', $oferta->id) }}"
                class="btn btn-warning"
            >
                Editar
            </a>

            {{--
                BOTÃO VOLTAR

                Retorna à listagem de ofertas de cursos.
            --}}
            <a
                href="{{ route('ofertas-cursos.index') }}"
                class="btn btn-secondary"
            >
                Voltar
            </a>

        </div>

    </div>

    {{-- ==========================================
         MENSAGEM DE SUCESSO
    ========================================== --}}

    {{--
        session('success') recupera a mensagem
        enviada pelo Controller após cadastrar
        ou atualizar uma oferta.

        A mensagem aparece temporariamente
        após o redirecionamento.
    --}}
    @if (session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            {{-- Exibe a mensagem de sucesso --}}
            {{ session('success') }}

            {{-- Botão para fechar a mensagem --}}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fechar"
            ></button>

        </div>

    @endif

    {{-- ==========================================
         CARTÃO COM AS INFORMAÇÕES DA OFERTA
    ========================================== --}}

    <div class="card shadow-sm">

        {{-- TÍTULO DO CARTÃO --}}
        <div class="card-header">

            <h5 class="mb-0">
                Informações da Oferta
            </h5>

        </div>

        {{-- CORPO DO CARTÃO --}}
        <div class="card-body">

            {{-- ==================================
                 NOME DO CURSO
            ================================== --}}
            <div class="mb-3">

                <strong>Curso:</strong>

                <p class="mb-0">
                    {{ $oferta->curso?->nome ?? 'Não informado' }}
                </p>

            </div>

            <hr>

            {{-- ==================================
                 SEMESTRE INICIAL
            ================================== --}}

            {{--
                Exibe o período letivo em que
                a oferta de curso começa.

                Exemplo: 2026.2

                O relacionamento semestre()
                está definido no Model OfertaCurso.
            --}}
            <div class="mb-3">

                <strong>Semestre Inicial:</strong>

                <p class="mb-0">
                    {{ $oferta->semestre?->identificador ?? 'Não informado' }}
                </p>

            </div>

            <hr>

            {{-- ==================================
                 SEMESTRE FINAL
            ================================== --}}

            {{--
                Exibe o último período letivo
                previsto para a oferta de curso.

                Exemplo: 2028.1

                O relacionamento semestreFinal()
                está definido no Model OfertaCurso.

                O operador ?-> evita erros
                quando o relacionamento é nulo.

                Isso é importante porque ofertas
                antigas podem não possuir
                semestre final cadastrado.
            --}}
            <div class="mb-3">

                <strong>Semestre Final:</strong>

                <p class="mb-0">
                    {{ $oferta->semestreFinal?->identificador ?? 'Não informado' }}
                </p>

            </div>

            <hr>

            {{-- ==================================
                 QUANTIDADE DE TURMAS
            ================================== --}}

            {{--
                Apresenta a quantidade de turmas
                associadas à oferta de curso.
            --}}
            <div class="mb-3">

                <strong>Quantidade de Turmas:</strong>

                <p class="mb-0">
                    {{ $oferta->quantidade_turmas }}
                </p>

            </div>

            <hr>

            {{-- ==================================
                 SITUAÇÃO DA OFERTA
            ================================== --}}

            {{--
                Exibe a situação atual da oferta.

                Planejada:
                Oferta ainda não iniciada.

                Em andamento:
                Oferta em execução.

                Concluída:
                Oferta finalizada.
            --}}
            <div class="mb-3">

                <strong>Situação:</strong>

                <p class="mb-0">

                    {{-- Oferta planejada --}}
                    @if ($oferta->situacao === 'Planejada')

                        <span class="badge bg-secondary">
                            Planejada
                        </span>

                    {{-- Oferta em andamento --}}
                    @elseif ($oferta->situacao === 'Em andamento')

                        <span class="badge bg-primary">
                            Em andamento
                        </span>

                    {{-- Oferta concluída --}}
                    @elseif ($oferta->situacao === 'Concluída')

                        <span class="badge bg-success">
                            Concluída
                        </span>

                    {{-- Outra situação cadastrada --}}
                    @else

                        {{ $oferta->situacao }}

                    @endif

                </p>

            </div>

            <hr>

            {{-- ==================================
                 OBSERVAÇÃO
            ================================== --}}

            {{--
                Exibe a observação registrada
                durante o cadastro ou edição.

                Caso esteja vazia, apresenta
                uma mensagem padrão.
            --}}
            <div class="mb-3">

                <strong>Observação:</strong>

                <p class="mb-0">
                    {{ $oferta->observacao ?: 'Nenhuma observação cadastrada.' }}
                </p>

            </div>

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo da página --}}
@endsection
