@extends('layouts.app')

@section('title', 'Editar Docente - ETS/UFPB')

@section('content')

<h2 class="mb-4">Editar Docente</h2>

<div class="card card-dashboard">
    <div class="card-body">

        <form action="{{ route('docentes.update', $docente) }}"
              method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nome Completo</label>
                <input type="text" name="nome"
                       class="form-control"
                       value="{{ old('nome', $docente->nome) }}"
                       required>
                @error('nome')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Colegiado</label>
                <input type="text" name="colegiado"
                       class="form-control"
                       value="{{ old('colegiado', $docente->colegiado) }}"
                       required>
                @error('colegiado')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Matrícula SIAPE</label>
                <input type="text" name="siape"
                       maxlength="20"
                       class="form-control"
                       value="{{ old('siape', $docente->siape) }}"
                       required>
                @error('siape')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('docentes.index') }}"
                   class="btn btn-secondary">
                    Voltar
                </a>

                <button type="submit" class="btn btn-warning">
                    Salvar Alterações
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
