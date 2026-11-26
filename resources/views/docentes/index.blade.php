@extends('layouts.app')

@section('title', 'Gerenciamento de Docentes')

@section('content')
{{-- Estilo compartilhado das listagens, sem alterar os dados. --}}
<link rel="stylesheet" href="{{ asset('css/ets-listagens.css') }}">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciamento de Docentes</h2>

    <a href="{{ route('docentes.create') }}"
       class="btn btn-primary">
        + Cadastrar Docente
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card card-dashboard">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped align-middle ets-listagem">

                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Colegiado</th>
                        <th>SIAPE</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($docentes as $docente)
                        <tr>
                            <td><a href="{{ route('docentes.show', $docente) }}" class="fw-semibold text-decoration-none">{{ $docente->nome }}</a></td>
                            <td>{{ $docente->colegiado }}</td>
                            <td>{{ $docente->siape }}</td>

                            <td>
                                <a href="{{ route('docentes.show', $docente) }}"
                                   class="btn btn-info btn-sm">
                                    Ver
                                </a>

                                <a href="{{ route('docentes.edit', $docente) }}"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <form action="{{ route('docentes.destroy', $docente) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Deseja realmente excluir este docente?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Nenhum docente cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
