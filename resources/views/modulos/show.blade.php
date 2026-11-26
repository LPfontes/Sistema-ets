@extends('layouts.app')
@section('title', 'Detalhes do Módulo')
@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1">{{ $modulo->nome }}</h2>
            <p class="text-muted mb-0">Curso: {{ $modulo->curso?->nome ?? 'Não informado' }}</p>
        </div>
        <a href="{{ route('modulos.index') }}" class="btn btn-outline-secondary">Voltar aos Módulos</a>
    </div>

    {{-- Indicadores do módulo e da soma das disciplinas cadastradas. --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body">
            <div class="text-muted small">Disciplinas vinculadas</div>
            <h3 class="mb-0">{{ $disciplinas->count() }}</h3>
        </div></div></div>
        <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body">
            <div class="text-muted small">Carga horária do módulo</div>
            <h3 class="mb-0">{{ $modulo->carga_horaria }}h</h3>
        </div></div></div>
        <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body">
            <div class="text-muted small">Soma das CH das disciplinas</div>
            <h3 class="mb-0">{{ $totalHorasDisciplinas }}h</h3>
        </div></div></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Disciplinas deste módulo</strong>
            <span class="text-muted small">Período curricular: {{ $modulo->periodo_oferta }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead><tr><th>Disciplina</th><th>Tipo</th><th>Categoria</th><th class="text-end">Carga horária</th></tr></thead>
                    <tbody>
                        @forelse($disciplinas as $disciplina)
                            <tr>
                                <td>{{ $disciplina->nome }}</td>
                                <td>{{ $disciplina->tipo ?? 'Não informado' }}</td>
                                <td>{{ $disciplina->categoria ?? 'Não informado' }}</td>
                                <td class="text-end">{{ $disciplina->carga_horaria }}h</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Nenhuma disciplina vinculada a este módulo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(auth()->user()?->perfil === 'admin')
            <div class="card-footer"><a href="{{ route('modulos.edit', $modulo) }}" class="btn btn-warning">Editar Módulo</a></div>
        @endif
    </div>
</div>
@endsection
