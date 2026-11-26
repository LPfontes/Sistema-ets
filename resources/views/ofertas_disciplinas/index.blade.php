{{--
====================================================
SISTEMA ETS/UFPB
LISTAGEM DE OFERTAS DE DISCIPLINAS

Esta página permite:
- Visualizar as ofertas cadastradas;
- Identificar disciplina, semestre e docente;
- Consultar a carga horária prevista;
- Acessar futuramente as ações do CRUD.

A variável $ofertas é enviada pelo método
index() do OfertaDisciplinaController.
====================================================
--}}

{{-- Reutiliza o layout principal do sistema --}}
@extends('layouts.app')

{{-- Define o título da página --}}
@section('title', 'Ofertas de Disciplinas')

{{-- Inicia o conteúdo principal --}}
@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="container-fluid">

    
    {{--
        MENSAGEM DE SUCESSO

        Exibe a mensagem enviada pelo Controller
        depois que uma oferta é cadastrada.
    --}}
    @if (session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            {{ session('success') }}

            {{-- Permite fechar a mensagem --}}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Fechar"
            ></button>

        </div>

    @endif


    {{-- CABEÇALHO DA PÁGINA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Ofertas de Disciplinas</h2>

            <p class="text-muted mb-0">
                Gerencie as disciplinas ofertadas em cada semestre.
            </p>
        </div>

        
        {{--
            BOTÃO NOVA OFERTA

            Abre o formulário de cadastro através
            da rota ofertas-disciplinas.create.
        --}}
        <a
            href="{{ route('ofertas-disciplinas.create') }}"
            class="btn btn-primary"
        >
            + Nova Oferta
        </a>


    </div>

    {{-- CARTÃO DA LISTAGEM --}}
    <div class="card shadow-sm">

        <div class="card-body">

            {{--
                Verifica se existem ofertas cadastradas.
                Se não existirem, apresenta uma mensagem.
            --}}
            @if ($ofertas->isEmpty())

                <div class="alert alert-info mb-0">

                    Nenhuma oferta de disciplina cadastrada.

                </div>

            @else

                {{--
                    table-responsive permite rolagem horizontal
                    em telas menores.
                --}}
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle ets-listagem">

                        {{-- CABEÇALHO DA TABELA --}}
                        <thead class="table-light">

                            <tr>

                                <th>Disciplina</th>
                                <th>Semestre</th>
                                <th>Docente</th>
                                <th>Carga Horária</th>
                                <th>Ações</th>
                            </tr>

                        </thead>

                        {{-- CORPO DA TABELA --}}
                        <tbody>

                            {{-- Percorre todas as ofertas cadastradas --}}
                            @foreach ($ofertas as $oferta)

                                <tr>
                                    {{-- Nome da disciplina relacionada --}}
                                    <td>
                                        {{ $oferta->disciplina?->nome ?? 'Não informada' }}
                                    </td>
                                    <td>
                                        {{ $oferta->semestre?->identificador ?? 'Não informado' }}
                                    </td>

                                    {{-- Nome do docente responsável --}}
                                    <td>
                                        {{ $oferta->docente?->nome ?? 'Não definido' }}
                                    </td>

                                    {{-- Carga horária prevista --}}
                                    <td>
                                        {{ $oferta->carga_horaria_prevista }}h
                                    </td>

                                                                        
                                    
                                    
                                {{--
                                    COLUNA AÇÕES

                                    Disponibiliza as operações:
                                    - Ver detalhes;
                                    - Editar oferta;
                                    - Excluir oferta.
                                --}}
                                <td class="text-nowrap">

                                    {{-- Mantém os botões alinhados horizontalmente --}}
                                    <div class="d-inline-flex align-items-center gap-1">

                                        {{-- BOTÃO VER --}}
                                        <a
                                            href="{{ route('ofertas-disciplinas.show', $oferta) }}"
                                            class="btn btn-sm btn-info"
                                        >
                                            Ver
                                        </a>

                                        {{-- BOTÃO EDITAR --}}
                                        <a
                                            href="{{ route('ofertas-disciplinas.edit', $oferta) }}"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Editar
                                        </a>

                                        {{--
                                            FORMULÁRIO DE EXCLUSÃO

                                            O formulário envia uma requisição DELETE
                                            para a rota de exclusão da oferta.

                                            @csrf protege contra requisições indevidas.
                                            @method('DELETE') identifica a operação.

                                            O confirm() solicita confirmação antes
                                            de enviar o formulário.
                                        --}}
                                        <form
                                            action="{{ route('ofertas-disciplinas.destroy', $oferta) }}"
                                            method="POST"
                                            class="d-inline-flex m-0"
                                            onsubmit="return confirm('Deseja realmente excluir esta oferta de disciplina?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                            >
                                                Excluir
                                            </button>

                                        </form>

                                    </div>

                                </td>




                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

{{-- Finaliza o conteúdo principal --}}
@endsection
