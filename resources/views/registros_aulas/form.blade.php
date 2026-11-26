@extends('layouts.app')
@section('title', $registro->exists ? 'Editar Registro de Aula' : 'Registrar Aula')
@section('content')
<div class="container-fluid">
    <h2>{{ $registro->exists ? 'Editar Registro de Aula' : 'Registrar Aula Realizada' }}</h2>
    @if($errors->any())
        <div class="alert alert-danger"><strong>Verifique os campos:</strong><ul class="mb-0">@foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul></div>
    @endif
    <div class="card shadow-sm"><div class="card-body p-4">
        <form method="POST" action="{{ $registro->exists ? route('registros-aulas.update', $registro) : route('registros-aulas.store') }}">
            @csrf
            @if($registro->exists) @method('PUT') @endif
            {{-- Cada aula deve apontar para uma oferta de disciplina já cadastrada. --}}
            <div class="mb-3">
                <label for="oferta_disciplina_id" class="form-label">Oferta de disciplina *</label>
                <select class="form-select" name="oferta_disciplina_id" id="oferta_disciplina_id" required>
                    <option value="">Selecione a oferta</option>
                    @foreach($ofertas as $oferta)
                        <option value="{{ $oferta->id }}" @selected(old('oferta_disciplina_id', $registro->oferta_disciplina_id ?? request('oferta_disciplina_id')) == $oferta->id)>
                            #{{ $oferta->id }} — {{ $oferta->disciplina?->nome }} — {{ $oferta->semestre?->identificador }} — {{ $oferta->docente?->nome ?? 'Sem docente' }} ({{ $oferta->carga_horaria_prevista }}h previstas)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="data_aula" class="form-label">Data da aula *</label>
                    <input type="date" class="form-control" id="data_aula" name="data_aula" max="{{ date('Y-m-d') }}" value="{{ old('data_aula', $registro->data_aula?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="horas_realizadas" class="form-label">Horas realizadas *</label>
                    <input type="number" class="form-control" id="horas_realizadas" name="horas_realizadas" min="1" max="24" value="{{ old('horas_realizadas', $registro->horas_realizadas) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="tipo_atividade" class="form-label">Tipo de atividade *</label>
                    <select class="form-select" id="tipo_atividade" name="tipo_atividade" required>
                        @foreach(['Teórica', 'Prática', 'Outra'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_atividade', $registro->tipo_atividade) === $tipo)>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="conteudo_ministrado" class="form-label">Conteúdo ministrado *</label>
                <textarea class="form-control" id="conteudo_ministrado" name="conteudo_ministrado" rows="4" maxlength="5000" required>{{ old('conteudo_ministrado', $registro->conteudo_ministrado) }}</textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a class="btn btn-secondary" href="{{ route('registros-aulas.index') }}">Cancelar</a>
                <button class="btn btn-primary">Salvar Aula</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
