@extends('layouts.app')

@section('title', 'Cadastrar Docente - ETS/UFPB')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">Cadastrar Novo Docente</h2>

    <div class="card card-dashboard">
        <div class="card-body">

            <form action="{{ route('docentes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome"
                           class="form-control"
                           value="{{ old('nome') }}" required>

                    @error('nome')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Colegiado</label>
                    <input type="text" name="colegiado"
                           class="form-control"
                           value="{{ old('colegiado') }}" required>

                    @error('colegiado')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Matrícula SIAPE</label>
                    <input type="text" name="siape"
                           maxlength="20"
                           class="form-control"
                           value="{{ old('siape') }}" required>

                    @error('siape')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('docentes.index') }}"
                       class="btn btn-secondary">
                        Voltar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Salvar Docente
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection
